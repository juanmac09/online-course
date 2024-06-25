<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class ContentReadRequest extends FormRequest
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
        $required = ($this->path() != 'api/content/disable') ? 'required' : '';
        $id = ($this->path() == 'api/content/disable')? 'exists:course_contents,id' : 'exists:courses,id';
        return [
            'id' => 'required|numeric|integer|'.$id,
            'perPage' => $required.'|integer|numeric|min:1',
            'page' =>$required.'|integer|numeric|min:1',
        ];
    }
}
