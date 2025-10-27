<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => [
                'string',
                'required',
                'max:15'
            ],
            'last_name' => [
                'string',
                'max:15',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length requirement (can adjust as needed)
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[\W_]/', // At least one special character
            ],
            'confirm_password' => [
                'required',
                'same:password', // Ensure it matches the password
            ],
            'phone_no' => [
                'required',
                'min:11',
                'max:11',
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'confirm_password.required' => 'The confirm password is required.',
            'confirm_password.same' => 'The confirm password must match the password.',
        ];
    }
}
