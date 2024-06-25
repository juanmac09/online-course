<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\CourseContent;

class UpdateContentOrderRequest extends FormRequest
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
            'id' => 'required|integer|exists:courses,id',
            'order' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    // Check if value is an array
                    if (!is_array($value)) {
                        return $fail("The $attribute field must be an array.");
                    }
                    
                    foreach ($value as $key => $val) {
                        if (!is_int($key) || !is_int($val)) {
                            return $fail("The $attribute field must be an array of integers where keys are IDs and values are new positions.");
                        }
                    }

                    // Validate that all content belongs to the specified course
                    $courseId = $this->id;
                    foreach ($value as $contentId => $position) {
                        $content = CourseContent::find($contentId);
                        if (!$content || $content->course_id != $courseId) {
                            return $fail("The content ID $contentId does not belong to the course ID $courseId.");
                        }
                    }
                },
            ],
            'order.*' => [
                'integer',
                Rule::exists('course_contents', 'id'), 
            ],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'id.required' => 'The course ID is required.',
            'id.integer' => 'The course ID must be an integer.',
            'id.exists' => 'The specified course ID does not exist.',
            'order.required' => 'The order field is required.',
            'order.array' => 'The order field must be an array.',
            'order.*.integer' => 'Each value in the order array must be an integer.',
            'order.*.exists' => 'The content ID :input does not exist.',
        ];
    }
}
