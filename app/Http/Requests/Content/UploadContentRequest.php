<?php

namespace App\Http\Requests\Content;

use Illuminate\Foundation\Http\FormRequest;

class UploadContentRequest extends FormRequest
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
            
   
            $required = $this->path() != 'api/content/update' ?  'required|':''; 
            $id = ($this->path() == 'api/content/update')? 'required|exists:course_contents,id' : '';
            return [
                'id' => $id,
                'title' =>$required.'string|max:255',
                'description' =>$required.'string|max:255',
                'course_id' =>$required.'exists:courses,id',
                'content' => $required.'file|mimes:jpeg,png,jpg,gif,mp4,avi,mov,wmv|max:10240'
            ];
        }
}
