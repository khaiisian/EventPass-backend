<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
            'UserName' => 'required|string|max:255',
            'Email' => 'required|email|max:255|unique:Tbl_User,Email,' . $this->UserId . ',UserId',
            'PhNumber' => 'nullable|string|max:20',
            'Password' => 'required|string|min:6',
            'ProfileImg' => 'nullable|string|max:255',
            'DeleteFlag' => 'nullable|boolean',
        ];
    }
}