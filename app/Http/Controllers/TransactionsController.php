<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\SubmitTransactionRequest;
use App\Services\TransactionsService;
use Illuminate\Http\JsonResponse;

class TransactionsController extends Controller
{
    private TransactionsService $transactionsService;

    public function __construct(TransactionsService $transactionsService)
    {
        $this->transactionsService = $transactionsService;
    }

    public function store(StoreTransactionRequest $request): JsonResponse
    {
        $this->transactionsService->store($request);

        //todo: return transaction resource
        return response()->json(['status' => 'Okay!']);
    }

    public function submit(SubmitTransactionRequest $request): JsonResponse
    {
        $requestData = $request->validated();
        $this->transactionsService->submit($requestData['transaction_id']);

        return response()->json(['message' => 'Submitted' ]);
    }
}
