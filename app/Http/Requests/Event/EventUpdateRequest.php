<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EventUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'EventTypeId' => 'nullable|integer|exists:Tbl_EventType,EventTypeId',
            'VenueId' => 'nullable|integer|exists:Tbl_Venue,VenueId',
            'OrganizerId' => 'nullable|integer|exists:Tbl_EventOrganizer,OrganizerId',

            'EventName' => 'nullable|string|max:255',

            'StartDate' => 'nullable|date',
            'EndDate' => 'nullable|date|after_or_equal:StartDate',

            'EventImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

            'IsActive' => 'nullable|boolean',
            'EventStatus' => 'nullable|integer|min:0',

            'TotalTicketQuantity' => 'nullable|integer|min:0',

            'TicketTypes' => 'required|array|min:1',

            'TicketTypes.*.TicketTypeCode' =>
                'nullable|string|exists:Tbl_TicketType,TicketTypeCode',

            'TicketTypes.*.TicketTypeName' => 'required|string|max:255',
            'TicketTypes.*.Price' => 'required|numeric|min:0',
            'TicketTypes.*.TotalQuantity' => 'required|integer|min:0',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'data' => null,
                'message' => $validator->errors()->first()
            ], 422)
        );
    }
}