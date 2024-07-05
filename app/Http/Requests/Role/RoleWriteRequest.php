<?php

namespace App\Http\Requests\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class RoleWriteRequest extends FormRequest
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
        $id = ($this->method() != 'POST') ? 'required|numeric|integer|exists:roles,id' : '';
        $unique = ($this->method() == 'POST') ? 'unique:roles,name' : '';
        return [
            'id' => $id,
            'name' => 'required|string|max:255|' . $unique,
        ];
    }


    /**
     * Adds custom validation logic after the main validation process.
     *
     * @param Validator $validator The instance of the validator.
     */
    protected function withValidator(Validator $validator)
    {
        if ($this->method() == 'PUT') {
            $role = Role::find($this->id);
            if ($role && $role->name != $this->name) {
                $roles = Role::where('name', $this->name)->get();
                if ($roles->count() >= 1) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('name', 'The name has already been taken.');
                    });
                }
            }
            else{
                $validator->after(function ($validator) {
                    $validator->errors()->add('id', 'The id field is invalid.');
                });
            }
        }
    }
}
