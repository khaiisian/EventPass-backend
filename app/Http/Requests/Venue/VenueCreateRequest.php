<?php

namespace App\Http\Requests\Venue;

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
            'VenueImage' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'Capacity' => 'nullable|integer|min:0',
        ];
    }

    // Return JSON response on validation failure
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