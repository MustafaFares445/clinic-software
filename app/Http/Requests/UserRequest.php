<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $rules =  [
            'firstName' => ['somtimes', 'string', 'min:2', 'max:255'],
            'lastName' => ['somtimes', 'string', 'min:2', 'max:255'],
            'email' => ['somtimes', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['somtimes', 'string', 'min:8', 'max:255'],
            'username' => ['somtimes', 'string', 'min:3', 'max:255', Rule::unique('users'  , 'username')->ignore($this->id)]
        ];

        if ($this->isMethod('POST')) {
            $rules['firstName'] = ['required', 'string', 'min:2', 'max:255'];
            $rules['lastName'] = ['required', 'string', 'min:2', 'max:255'];
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users,email'];
            $rules['password'] = ['required', 'string', 'min:8', 'max:255'];
            $rules['username'] = ['required', 'string', 'min:3', 'max:255', 'unique:users,username'];
       }

        return $rules;
    }
}
