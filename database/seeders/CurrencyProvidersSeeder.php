<?php

namespace Database\Seeders;

use App\Enums\CurrencyEnum;
use App\Models\CurrencyProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencyProvidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('currency_providers')->truncate();
        $data = [
            [
                'name' => 'megacash',
                'currencies' => json_encode([CurrencyEnum::EUR]),
            ],
            [
                'name' => 'supermoney',
                'currencies' => json_encode([CurrencyEnum::USD])
            ]
        ];

        DB::table('currency_providers')->insert($data);
    }
}
