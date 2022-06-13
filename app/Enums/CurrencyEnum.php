<?php

namespace App\Enums;

use App\Enums\Traits\HasValue;

enum CurrencyEnum : string
{
    use HasValue;

    case EUR = 'eur';
    case USD = 'usd';
}
