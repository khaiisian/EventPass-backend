<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'UserName' => 'nullable|string|max:255',
            'Email' => 'nullable|email|max:255',
            'PhNumber' => 'nullable|string|max:20',
            'Password' => 'nullable|string|min:6',
            'ProfileImg' => 'nullable|string|max:255',
            'DeleteFlag' => 'nullable|boolean',
        ];
    }
}