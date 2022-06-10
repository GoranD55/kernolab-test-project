<?php

namespace App\Enums;

enum TransactionStatusEnum : string
{
    case NEW = 'new';
    case SUBMITTED = 'submitted';
    case COMPLETED = 'completed';
}
