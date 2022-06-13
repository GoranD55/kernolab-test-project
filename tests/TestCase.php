<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected const TRANSACTION_JSON_SCHEMA = [
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
}
