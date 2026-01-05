<?php

namespace App\Http\Requests\VenueType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class VenueTypeUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $venueTypeId = $this->route('id');
        return [
            'VenueTypeName' => 'nullable|string|max:255|unique:Tbl_VenueType,VenueTypeName,' . $venueTypeId . ',VenueTypeId',

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