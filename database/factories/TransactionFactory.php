<?php

namespace Database\Factories;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => rand(1, 10),
            'details' => Str::random(12),
            'receiver_account' => $this->faker->iban(),
            'receiver_name' => $this->faker->name(),
            'amount' => rand(1, 300),
            'currency' => $this->faker->randomElement(CurrencyEnum::values()),
            'fee' => rand(1, 30),
            'status' => TransactionStatusEnum::NEW,
        ];
    }
}
