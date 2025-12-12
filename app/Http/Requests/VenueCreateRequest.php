<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VenueCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'VenueName' => 'required|string|max:255|unique:Tbl_Venue,VenueName',
            'VenueTypeId' => 'required|integer|exists:Tbl_VenueType,VenueTypeId',
            'Description' => 'nullable|string|max:1000',
            'Address' => 'nullable|string|max:500',
            'VenueImage' => 'nullable|string|max:255',
            'Capacity' => 'nullable|integer|min:0',
        ];
    }

    // Return JSON response on validation failure
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'data' => null,
            'message' => $validator->errors()->first()
        ], 422));
    }
}