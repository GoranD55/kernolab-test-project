<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Exceptions\LimitForCreatingTransactionException;
use App\Jobs\CompleteTransaction;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

final class TransactionsService
{
    public function index(int $user_id): Collection
    {
        return Transaction::query()->where('user_id', $user_id)->get();
    }

    public function show(int $transaction_id): Transaction | null
    {
        return Transaction::query()->find($transaction_id);
    }

    public function store(array $requestData): Transaction
    {
        if (!$this->canUserStoreTransaction($requestData)) {
            throw new LimitForCreatingTransactionException('You have reached the transaction limit', 400);
        }

        $commissionService = new CommissionService($requestData['user_id'], $requestData['amount']);

        try {
            DB::beginTransaction();
            $transaction = Transaction::create(
                array_merge($requestData, [
                    'details' => (new CurrencyProviderService())->formatTransactionDetails($requestData['details'], $requestData['currency']),
                    'fee' => $commissionService->getFeeValue(),
                    'status' => TransactionStatusEnum::NEW
                ])
            );
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollback();
            throw $exception;
        }

        return $transaction;
    }

    public function submit(int $transaction_id): void
    {
        $transaction = Transaction::query()->findOrFail($transaction_id);
        try {
            DB::beginTransaction();
            $transaction->update(['status' => TransactionStatusEnum::SUBMITTED]);

            dispatch(new CompleteTransaction($transaction));
            DB::commit();
        } catch (QueryException $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    private function canUserStoreTransaction(array $requestData): bool
    {
        return !$this->isLimitForQuantity($requestData['user_id']) &&
                !$this->isLimitForTotalAmount($requestData);
    }

    private function isLimitForQuantity(int $user_id): bool
    {
        $transactionsCountPerPeriod = Transaction::query()
            ->where([
                ['user_id', $user_id],
                ['created_at', '>=', Carbon::now()->subHour()]
            ])->count();

        return $transactionsCountPerPeriod >= config('transaction.count_per_period');
    }

    private function isLimitForTotalAmount(array $requestData): bool
    {
        $transactions = Transaction::query()
            ->where('currency', $requestData['currency'])
            ->select('amount', 'fee')
            ->get();

        $commissionService = new CommissionService($requestData['user_id'], $requestData['amount']);
        $currentTransactionTotalAmount = $commissionService->getFeeValue() + $requestData['amount'];

        return $transactions->sum('total_amount') + $currentTransactionTotalAmount
            >= config('transaction.max_total_amount_for_currency');
    }
}
