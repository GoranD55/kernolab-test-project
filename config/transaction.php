<?php

return [
    'count_per_period' => env('COUNT_OF_TRANSACTIONS_PER_PERIOD', 10),
    'time_period_in_minutes' => env('TIME_PERIOD_IN_MINUTES', 60),
    'max_total_amount_for_currency' => env('MAX_TOTAL_AMOUNT_FOR_CURRENCY', 1000),
];
