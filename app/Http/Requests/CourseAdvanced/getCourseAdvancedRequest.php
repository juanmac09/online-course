<?php

namespace App\Http\Requests\CourseAdvanced;

use Illuminate\Foundation\Http\FormRequest;

class getCourseAdvancedRequest extends FormRequest
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
            'id' => 'numeric|integer|exists:users,id',
            'perPage' => 'required|integer|numeric|min:1',
            'page' =>'required|integer|numeric|min:1'
        ];
    }
}
