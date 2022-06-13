<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\SubmitTransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionsService;
use Exception;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    private TransactionsService $transactionsService;

    public function __construct(TransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    public function store(StoreTransactionRequest $request): TransactionResource | JsonResponse
    {
        try {
            $transaction = $this->transactionsService->store($request->validated());

            return new TransactionResource($transaction);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'reason' => $exception->getMessage(),
            ], 400);
        }
    }

    public function submit(SubmitTransactionRequest $request): JsonResponse
    {
        try {
            $requestData = $request->validated();
            $this->transactionsService->submit($requestData['transaction_id']);
        } catch (Exception $exception) {
            return response()->json([
                'status' => false,
                'reason' => $exception->getMessage(),
            ], 400);
        }

        return response()->json(['message' => 'Successful!']);
    }
}
