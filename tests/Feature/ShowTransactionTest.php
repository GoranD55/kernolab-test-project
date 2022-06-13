<?php

namespace Tests\Feature;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShowTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
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

        $response = $this->getJson($this->getRoute($transaction['id']));

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => self::TRANSACTION_JSON_SCHEMA
            ]);
    }

    public function test_not_found(): void
    {
        $response = $this->getJson($this->getRoute('22'));

        $response->assertStatus(404)
            ->assertJson([
                'status' => false,
                'reason' => 'Transaction not found!'
            ]);
    }

    private function getRoute(string $transaction_id): string
    {
        return route('api.transactions.show', [$transaction_id]);
    }
}
