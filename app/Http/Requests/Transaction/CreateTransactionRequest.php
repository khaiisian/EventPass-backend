<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateTransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'UserId' => 'required|integer|exists:users,id',
            'EventId' => 'required|integer|exists:Tbl_Event,EventId',
            'PaymentType' => 'required|string|max:50',

            'Tickets' => 'required|array|min:1',
            'Tickets.*.TicketTypeCode' => 'required|string|max:255',
            'Tickets.*.Quantity' => 'required|integer|min:1',
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