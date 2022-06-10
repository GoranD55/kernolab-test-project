<?php

namespace App\Models;

use App\Enums\CurrencyEnum;
use App\Enums\TransactionStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
       'user_id',
       'details',
       'receiver_account',
       'receiver_name',
       'amount',
       'currency',
       'fee',
       'status'
    ];

    protected $casts = [
        'currency' => CurrencyEnum::class,
        'status' => TransactionStatusEnum::class,
    ];

    public function getTotalAmountAttribute() : int
    {
        return $this->amount + $this->fee;
    }
}
