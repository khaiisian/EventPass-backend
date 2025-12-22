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
            'TicketTypeCode' => 'required|string|max:255|unique:Tbl_TicketType,TicketTypeCode',
            'EventId' => 'required|integer|exists:Tbl_Event,EventId',
            'TicketTypeName' => 'required|string|max:255',
            'Price' => 'nullable|numeric|min:0',
            'TotalQuantity' => 'nullable|integer|min:0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'data' => null,
            'message' => $validator->errors()->first()
        ], 422));
    }
}