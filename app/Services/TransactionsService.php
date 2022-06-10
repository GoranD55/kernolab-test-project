<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Exceptions\LimitForCreatingTransactionException;
use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class TransactionsService
{
    protected Request $request;

    public function store(StoreTransactionRequest $request): Transaction
    {
        $this->request = $request;

        if (!$this->canUserStoreTransaction()) {
            throw new LimitForCreatingTransactionException('You have reached the transaction limit');
        }

        $commissionService = new CommissionService();
        $feePercentage = $commissionService->getCurrentFeePercentage($request->input('user_id'));

        return Transaction::create(
            array_merge($request->all(), [
                'fee' => $commissionService->calculateFeeValue($request->input('amount'), $feePercentage),
                'status' => TransactionStatusEnum::NEW
            ])
        );
    }

    public function submit(int $transaction_id): void
    {
        Transaction::query()
            ->where('id', $transaction_id)
            ->update(['status' => TransactionStatusEnum::SUBMITTED]);
    }

    private function canUserStoreTransaction(): bool
    {
        return !$this->isLimitForQuantity() && !$this->isLimitForTotalAmount();
    }

    private function isLimitForQuantity(): bool
    {
        $transactionsCountPerPeriod = Transaction::query()->where([
            ['user_id', $this->request['user_id']],
            ['created_at', '>=', Carbon::now()->subHour()]
        ])->count();

        return $transactionsCountPerPeriod >= config('transaction.count_per_period');
    }

    private function isLimitForTotalAmount(): bool
    {
        $transactions = Transaction::query()
            ->where('currency', $this->request['currency'])
            ->select('amount', 'fee')
            ->get();

        // append total amount for a new transaction, result must be less than 1000

        return $transactions->sum('total_amount') >= config('transaction.max_total_amount_for_currency');
    }
}
