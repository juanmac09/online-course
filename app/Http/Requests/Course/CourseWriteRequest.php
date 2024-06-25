<?php

namespace App\Http\Requests\Course;

use Illuminate\Foundation\Http\FormRequest;

class CourseWriteRequest extends FormRequest
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
        $id = ($this->method() != 'POST') ? 'required|numeric|integer|exists:courses,id' : '';
        $required = ($this->method() == 'POST') ? 'required|' : '';
        return [
            'id' => $id,
            'title' => $required . 'string|max:255',
            'description' => $required . 'string|max:255',
        ];
    }
}
