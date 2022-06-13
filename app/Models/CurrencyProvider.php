<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CurrencyProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'currencies',
    ];

    protected $casts = [
        'currencies' => 'json'
    ];
}
