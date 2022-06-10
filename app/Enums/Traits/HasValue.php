<?php

namespace App\Enums\Traits;

trait HasValue
{
    public static function values() : array
    {
        return array_map(static function ($item) {
            return $item->value;
        }, self::cases());
    }
}
