<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserWriteRequest extends FormRequest
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
        $id = ($this -> method() != 'POST') ? 'required|numeric|integer|exists:users,id' : '';
        $email = ($this -> method() == 'POST') ? 'required|unique:users,email' : '';
        $required = ($this -> method() == 'POST') ? 'required|' : '';
        return [
            'id' => $id,
            'name' => $required.'string|max:255',
            'email' =>'string|email|max:255|'.$email,
            'role_id' => $required.'numeric|integer|exists:roles,id',
        ];
    }
}
