<?php

namespace App\Http\Requests\TicketType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class TicketTypeUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ticketTypeId = $this->route('id'); // get the ticket type id from route for unique check

        return [
            'TicketTypeCode' => 'nullable|string|max:255|unique:Tbl_TicketType,TicketTypeCode,' . $ticketTypeId . ',TicketTypeId',
            'EventId' => 'nullable|integer|exists:Tbl_Event,EventId',
            'TicketTypeName' => 'nullable|string|max:255',
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