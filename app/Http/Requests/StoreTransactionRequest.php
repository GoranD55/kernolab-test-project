<?php

namespace App\Http\Requests;

use App\Enums\CurrencyEnum;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer'],
            'details' => ['required', 'string'],
            'receiver_account' => ['required', 'string'],
            'receiver_name' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'currency' => ['required', Rule::in(CurrencyEnum::values())]
        ];
    }
}
