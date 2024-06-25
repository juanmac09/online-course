<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class getPublicContentRequest extends FormRequest
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
            'id' => 'required|integer|numeric|exists:courses,id',
            'perPage' => 'required|integer|numeric|min:1',
            'page' =>'required|integer|numeric|min:1',
        ];
    }
}
