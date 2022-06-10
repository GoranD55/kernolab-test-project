<?php

namespace App\Services;

use App\Models\Transaction;
use Carbon\Carbon;

final class CommissionService
{
    public function getCurrentFeePercentage(int $user_id): int
    {
        $groupedTransactionsAmount = Transaction::query()
            ->where('user_id', $user_id)
            ->whereDate('created_at', Carbon::today())
            ->selectRaw('SUM(amount + fee) as total_amount_value, currency')
            ->groupBy('currency')
            ->get();

        $isLowFee = $groupedTransactionsAmount->some(function($item) {
            return $item['total_amount_value'] > 100;
        });

        return $isLowFee
            ? config('transaction.low_fee_percentage')
            : config('transaction.high_fee_percentage');
    }

    public function calculateFeeValue(int $amount, int $fee_in_percentage): float
    {
        return $amount * $fee_in_percentage / 100;
    }
}
