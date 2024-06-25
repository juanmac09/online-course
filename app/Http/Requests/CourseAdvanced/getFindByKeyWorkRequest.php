<?php

namespace App\Http\Requests\CourseAdvanced;

use Illuminate\Foundation\Http\FormRequest;

class getFindByKeyWorkRequest extends FormRequest
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
        $id = ($this -> path() == 'api/search/searchEncorrollments') ? 'integer|numeric|exists:users,id' : '' ;
        return [
            'id' => $id,
            'keyword' =>'required|string',
            'perPage' =>'required|integer|numeric|min:1',
            'page' =>'required|integer|numeric|min:1',
        ];
    }
}
