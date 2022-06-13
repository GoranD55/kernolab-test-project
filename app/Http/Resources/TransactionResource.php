<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'transaction_id' => $this->id,
            'user_id' => $this->user_id,
            'details' => $this->details,
            'receiver_account' => $this->receiver_account,
            'receiver_name' => $this->receiver_name,
            'amount' => $this->amount,
            'currency' => $this->currency,
            'fee' => $this->fee,
            'status' => $this->status,
        ];
    }
}
