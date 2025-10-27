<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChangePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'old_password' => [
                'required',
                'string',
                'min:8',
                function ($attribute, $value, $fail) {
                    // Check if the old password matches the stored password
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('The provided old password does not match our records.');
                    }
                },
            ],
            'password' => [
                'required',
                'string',
                'min:8', // Minimum length
                'regex:/[a-z]/', // At least one lowercase letter
                'regex:/[A-Z]/', // At least one uppercase letter
                'regex:/[0-9]/', // At least one number
                'regex:/[\W_]/', // At least one special character
            ],
            'confirm_password' => [
                'required',
                'same:password', // Must match the password
            ],
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'confirm_password.required' => 'Confirm password is required.',
            'confirm_password.same' => 'Confirm password must match the password.',
        ];
    }
}
