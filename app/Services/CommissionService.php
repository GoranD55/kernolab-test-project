<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;

final class CommissionService
{
    private int $user_id;
    private float $amount;

    public function __construct(int $user_id, int $amount)
    {
        $this->user_id = $user_id;
        $this->amount = $amount;
    }

    public function getFeeValue(): int
    {
        $feePercentage = $this->getCurrentFeePercentage($this->user_id);
        return $this->calculateFeeValue($this->amount, $feePercentage);
    }

    private function getCurrentFeePercentage(int $user_id): int
    {
        $groupedTransactionsAmount = Transaction::query()
            ->where('user_id', $user_id)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(amount + fee) as total_amount_value, currency')
            ->groupBy('currency')
            ->get();

        $isLowFee = $groupedTransactionsAmount->some(fn($item) => $item['total_amount_value'] > 100);

        return $isLowFee
            ? config('transaction.low_fee_percentage')
            : config('transaction.high_fee_percentage');
    }

    private function calculateFeeValue(int $amount, int $fee_in_percentage): float
    {
        return $amount * $fee_in_percentage / 100;
    }
}
