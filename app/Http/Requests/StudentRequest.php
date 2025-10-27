<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{
    public function rulesForCreate()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_no' => 'required',
            'password' => 'required|min:6',
        ];
    }

    public function rulesForUpdate()
    {
        return [];
    }

    public function messages()
    {
        return [
            'name.required' => 'This field is required.',
            'email.required' => 'This field is required.',
            'phone_no.required' => 'This field is required.',
            'password.required' => 'This field is required.',
        ];
    }
}
