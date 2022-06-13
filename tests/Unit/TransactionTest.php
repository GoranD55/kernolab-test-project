<?php

namespace Tests\Unit;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    public function test_get_total_amount(): void
    {
        $transactionData = [
            'user_id' => rand(10, 20),
            'details' => Str::random(12),
            'receiver_account' => Str::random(12),
            'receiver_name' => Str::random(12),
            'amount' => rand(1, 300),
            'currency' => CurrencyEnum::EUR,
            'fee' => rand(1, 30),
            'status' => TransactionStatusEnum::NEW,
        ];

        $transaction = Transaction::create($transactionData);

        $testTotalAmount = $transaction['amount'] + $transaction['fee'];

        $this->assertEquals($testTotalAmount, $transaction->total_amount);
    }
}
