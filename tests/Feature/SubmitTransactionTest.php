<?php

namespace Tests\Feature;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class SubmitTransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_success(): void
    {
        //There are some problems with the Address provider in the faker
//        $requestData = Transaction::factory()->create();
        $transactionData = [
            'user_id' => rand(1, 10),
            'details' => Str::random(12),
            'receiver_account' => Str::random(12),
            'receiver_name' => Str::random(12),
            'amount' => rand(1, 300),
            'currency' => CurrencyEnum::EUR,
            'fee' => rand(1, 30),
            'status' => TransactionStatusEnum::NEW,
        ];

        $transaction = Transaction::create($transactionData);

        $requestData = [
            'transaction_id' => $transaction['id'],
            'code' => config('transaction.submit_code')
        ];

        $response = $this->postJson($this->getRoute(), $requestData);

        $response->assertSuccessful()
            ->assertJson([
                'message' => 'Successful!'
            ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction['id'],
            'status' => TransactionStatusEnum::COMPLETED
        ]);
    }

    public function test_non_exist_model(): void
    {
        $requestData = [
            'transaction_id' => rand(10, 20),
            'code' => config('transaction.submit_code')
        ];

        $response = $this->postJson($this->getRoute(), $requestData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The selected transaction id is invalid.'
            ]);
    }

    private function getRoute(): string
    {
        return route('api.transactions.submit');
    }
}
