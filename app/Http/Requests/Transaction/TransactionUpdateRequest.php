<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TransactionUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $TransactionId = $this->route('id');

        return [
            'Status' => 'nullable|boolean',
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