<?php

namespace App\Http\Requests\Comments;

use Illuminate\Foundation\Http\FormRequest;

class CommentsWriteRequest extends FormRequest
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
        $id = ($this -> path() == 'api/comments/update' || $this -> path() == 'api/comments/disable') ? 'required|integer|numeric|exists:comments,id' : '';
        $required = ($this -> path() == 'api/comments/create') ? 'required' : '';
        $comment = ($this -> path() == 'api/comments/create' || $this -> path() == 'api/comments/update') ? 'required' : '';
        return [
            'id' => $id,
            'content_id' => $required.'|integer|numeric|exists:course_contents,id',
            'comment' => $comment.'|string|max:530|min:1'
        ];
    }
}
