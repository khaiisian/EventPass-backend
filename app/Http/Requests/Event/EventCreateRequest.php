<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'EventTypeId' => 'required|integer|exists:Tbl_EventType,EventTypeId',
            'VenueId' => 'required|integer|exists:Tbl_Venue,VenueId',
            'EventName' => 'required|string|max:255',

            'StartDate' => 'nullable|date',
            'EndDate' => 'nullable|date|after_or_equal:StartDate',

            'IsActive' => 'nullable|boolean',
            'EventStatus' => 'nullable|integer|min:0',

            'TotalTicketQuantity' => 'required|integer|min:0',
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