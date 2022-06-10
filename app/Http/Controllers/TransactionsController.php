<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Services\TransactionsService;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $transaction = (new TransactionsService())->store($request);
        \Log::info($transaction);
        return response()->json(['status' => 'Okay!']);
    }
}
