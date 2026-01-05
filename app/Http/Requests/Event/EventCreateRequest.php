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

            'OrganizerId' => 'nullable|integer|exists:Tbl_EventOrganizer,OrganizerId',

            'StartDate' => 'nullable|date',
            'EndDate' => 'nullable|date|after_or_equal:StartDate',

            'EventImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'IsActive' => 'nullable|boolean',
            'EventStatus' => 'nullable|integer|min:0',

            'TotalTicketQuantity' => 'required|integer|min:0',

            'TicketTypes' => 'required|array|min:1',
            'TicketTypes.*.TicketTypeName' => 'required|string|max:255',
            'TicketTypes.*.Price' => 'required|numeric|min:0',
            'TicketTypes.*.TotalQuantity' => 'required|integer|min:0',
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