<?php

use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'transactions', 'as' => '.transactions'], function() {
    Route::post('', [TransactionsController::class, 'store']);
    Route::post('/submit', [TransactionsController::class, 'submit']);
    Route::get('', [TransactionsController::class, 'index']);
    Route::get('{transaction_id}', [TransactionsController::class, 'show']);
});

