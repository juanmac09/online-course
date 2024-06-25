<?php

namespace App\Http\Requests\Qualification;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use App\Models\Qualification;
use Illuminate\Support\Facades\Auth;

class QualificationCreateRequest extends FormRequest
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
            'qualification' => 'required|numeric|integer|between:1,5',
            'course_id' => 'required|integer|numeric|exists:courses,id',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     * @return void
     */
    protected function withValidator(Validator $validator)
    {
        $userId = Auth::user()->id;
        $courseId = $this->input('course_id');

        if ($this->method() == 'POST') {
            $validator->after(function ($validator) use ($userId, $courseId) {


                if (Qualification::where('user_id', $userId)->where('course_id', $courseId)->exists()) {
                    $validator->errors()->add('user_id', 'The user has already been qualified for this course.');
                    $validator->errors()->add('course_id', 'The course has already been qualified by this user.');
                }
            });
        }

        if ($this->method() == 'PUT') {
            $validator->after(function ($validator) use ($userId, $courseId) {
                if (!Qualification::where('user_id', $userId)->where('course_id', $courseId)->exists()) {
                    $validator->errors()->add('user_id', 'The user has not rated this course.');
                    $validator->errors()->add('course_id', 'The course has not been rated by this user.');
                }
            });
        }
    }
}
