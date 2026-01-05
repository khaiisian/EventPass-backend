<?php

namespace App\Http\Requests\TicketType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TicketTypeCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'EventId' => 'required|integer|exists:Tbl_Event,EventId',
            'TicketTypeName' => 'required|string|max:255',
            'Price' => 'nullable|numeric|min:0',
            'TotalQuantity' => 'nullable|integer|min:0',
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