<?php

namespace App\Services;

use App\Models\CurrencyProvider;

final class CurrencyProviderService
{
    public function formatTransactionDetails(string $detailsStr, string $currency): string
    {
        $currencyProvider = CurrencyProvider::query()
            ->whereJsonContains('currencies', $currency)->value('name');

        return match ($currencyProvider) {
            'megacash' => $this->changeStringByMegacashProvider($detailsStr),
            'supercash' => $this->changeStringBySupermoneyProvider($detailsStr),
            default => $detailsStr,
        };
    }

    private function changeStringByMegacashProvider(string $string): string
    {
        return substr($string, 0, 20);
    }

    private function changeStringBySupermoneyProvider(string $string): string
    {
        return $string . rand(0, 9);
    }
}
