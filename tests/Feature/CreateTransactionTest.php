<?php

namespace Tests\Feature;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class CreateTransactionTest extends TestCase
{
    use RefreshDatabase;

    private const TRANSACTION_JSON_SCHEMA = [
        'transaction_id',
        'user_id',
        'details',
        'receiver_account',
        'receiver_name',
        'amount',
        'currency',
        'fee',
        'status',
    ];

    public function test_success(): void
    {
        //There are some problems with the Address provider in the faker
//        $requestData = Transaction::factory()->make();
        $requestData = [
            'user_id' => rand(1, 10),
            'details' => Str::random(12),
            'receiver_account' => Str::random(12),
            'receiver_name' => Str::random(12),
            'amount' => rand(1, 300),
            'currency' => CurrencyEnum::EUR,
            'fee' => rand(1, 30),
            'status' => TransactionStatusEnum::NEW,
        ];

        $response = $this->postJson($this->getRoute(), $requestData);

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => self::TRANSACTION_JSON_SCHEMA
            ]);
    }

    public function test_transaction_limit(): void
    {
        $requestData = [
            'user_id' => rand(1, 10),
            'details' => Str::random(12),
            'receiver_account' => Str::random(12),
            'receiver_name' => Str::random(12),
            'amount' => rand(1000, 3000),
            'currency' => CurrencyEnum::EUR,
            'fee' => rand(10, 300),
            'status' => TransactionStatusEnum::NEW,
        ];

        $response = $this->postJson($this->getRoute(), $requestData);

        $response->assertStatus(400)
            ->assertJson([
                'status' => false,
                'reason' => 'You have reached the transaction limit'
            ]);
    }

    private function getRoute(): string
    {
        return route('api.transactions.store');
    }
}
