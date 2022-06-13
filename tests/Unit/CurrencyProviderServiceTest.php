<?php

namespace Tests\Unit;

use App\Enums\CurrencyEnum;
use App\Services\CurrencyProviderService;
use Database\Seeders\CurrencyProvidersSeeder;
use Illuminate\Support\Str;
use Tests\TestCase;

class CurrencyProviderServiceTest extends TestCase
{
    public function test_format_details(): void
    {
        $this->seed(CurrencyProvidersSeeder::class);

        $currencyProviderService = new CurrencyProviderService();
        $detailsString = Str::random('25');
        $resultDetailsString = $currencyProviderService->formatTransactionDetails($detailsString, CurrencyEnum::EUR->value);

        $this->assertEquals(substr($detailsString, 0, 20), $resultDetailsString);

        $resultDetailsString = $currencyProviderService->formatTransactionDetails($detailsString, CurrencyEnum::USD->value);

        $this->assertStringStartsWith($detailsString, $resultDetailsString);
    }
}
