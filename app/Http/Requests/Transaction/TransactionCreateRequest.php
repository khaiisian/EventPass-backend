<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'UserId' => 'required|integer|exists:Tbl_User,UserId',
            'Email' => 'required|email|max:255',
            'Status' => 'nullable|boolean',
            'PaymentType' => 'nullable|string|max:100',
            'TotalAmount' => 'nullable|numeric|min:0',
            'TransactionDate' => 'nullable|date',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $messages = implode(' ', $validator->errors()->all());

        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => $messages,
                'data' => null
            ], 422)
        );
    }
}