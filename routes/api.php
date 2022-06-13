<?php

use App\Http\Controllers\TransactionsController;
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

Route::group(['as' => 'api.'], function() {
    Route::group(['prefix' => 'transactions', 'as' => 'transactions.'], function() {
        Route::post('', [TransactionsController::class, 'store'])->name('store');
        Route::post('/submit', [TransactionsController::class, 'submit'])->name('submit');
        Route::get('', [TransactionsController::class, 'index'])->name('index');
        Route::get('{transaction_id}', [TransactionsController::class, 'show'])->name('show');
    });
});


