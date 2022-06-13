<?php

namespace Tests\Feature;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class GetTransactionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        //There are some problems with the Address provider in the faker
//        $requestData = Transaction::factory()->create();
        $transactionData = [
            'user_id' => 1,
            'details' => Str::random(12),
            'receiver_account' => Str::random(12),
            'receiver_name' => Str::random(12),
            'amount' => rand(1, 300),
            'currency' => CurrencyEnum::EUR,
            'fee' => rand(1, 30),
            'status' => TransactionStatusEnum::NEW,
        ];

        Transaction::create($transactionData);
        Transaction::create($transactionData);
        Transaction::create($transactionData);

        $response = $this->get($this->getRoute($transactionData['user_id']));

        $response->assertSuccessful()
            ->assertJsonStructure([
                'data' => [
                    0 =>self::TRANSACTION_JSON_SCHEMA
                ]
            ]);
    }

    public function test_invalid_data(): void
    {
        $response = $this->getJson($this->getRoute());

        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'user_id' => 'The user id field is required.'
            ]);
    }

    private function getRoute(int $user_id = null): string
    {
        return route('api.transactions.index', ['user_id' => $user_id]);
    }
}
