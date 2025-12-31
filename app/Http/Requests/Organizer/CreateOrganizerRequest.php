<?php

namespace App\Http\Requests\Organizer;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrganizerRequest extends FormRequest
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
        return [
            'OrganizerName' => 'required|string|max:255',
            'Email' => 'nullable|email|unique:Tbl_EventOrganizer,Email|max:255',
            'PhNumber' => 'nullable|string|max:50',
            'Address' => 'nullable|string|max:500',
            'CreatedBy' => 'nullable|string|max:255',
            'DeleteFlag' => 'boolean',
        ];

    }
}