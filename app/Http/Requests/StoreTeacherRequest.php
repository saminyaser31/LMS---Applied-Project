<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTeacherRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isCoverImgRequired = $isBioRequired = $isDetailedInfoRequired = 'nullable';
        if (Auth::user()) {
            // $isCoverImgRequired = 'required';
            $isBioRequired = 'required';
            $isDetailedInfoRequired = 'required';
        }

        return [
            'first_name' => [
                'string',
                'required',
                'max:30'
            ],
            'last_name' => [
                'string',
                'required', // Made required since it is marked as required in the form
                'max:50',
            ],
            'email' => [
                'required',
                'email',
                'unique:users',
            ],
            'phone_no' => [
                'required',
                'digits:11', // Ensures exactly 11 digits
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
            'dob' => [
                'required',
                'date', // Ensures a valid date format
                'before:today', // Ensures the date is in the past
            ],
            'address' => [
                'nullable', // Optional field
                'string',
                'max:255',
            ],
            'gender' => [
                'required',
                // 'in:male,female,other', // Assuming values based on standard gender options
            ],
            'marital_status' => [
                'required',
                // 'in:single,married,divorced,widowed', // Assuming common marital status values
            ],
            'religion' => [
                'required',
                // 'in:islam,christianity,hinduism,buddhism,other', // Assuming possible values
            ],
            'image' => [
                'required',
                'file',
                'mimes:jpeg,png,jpg,gif', // Specifies acceptable file types
                'max:2048', // Max file size of 2MB
                'dimensions:min_width=1500,min_height=1500,max_width=1500,max_height=1500',
            ],
            'nid_no' => [
                'nullable',
                'digits:10', // Assuming a 10-digit NID number (adjust if necessary)
            ],
            'nid_front_image' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif',
                'max:2048', // Max file size of 2MB
            ],
            'nid_back_image' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif',
                'max:2048', // Max file size of 2MB
            ],
            'cover_image' => [
                'nullable',
                'file',
                'mimes:jpeg,png,jpg,gif', // Specifies acceptable file types
                'max:2048', // Max file size of 2MB
                'dimensions:min_width=2610,min_height=700,max_width=2610,max_height=700',
            ],
            'bio' => [
                $isBioRequired,
                'string',
                'max:250',
            ],
            'detailed_info' => [
                $isDetailedInfoRequired,
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.max' => 'First name cannot exceed 15 characters.',
            'last_name.required' => 'Last name is required.',
            'last_name.max' => 'Last name cannot exceed 15 characters.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone_no.required' => 'Phone number is required.',
            'phone_no.digits' => 'Phone number must be exactly 11 digits.',
            'password.required' => 'The password is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.regex' => 'The password must include at least one lowercase letter, one uppercase letter, one number, and one special character.',
            'confirm_password.required' => 'Confirm password is required.',
            'confirm_password.same' => 'Confirm password must match the password.',
            'dob.required' => 'Date of birth is required.',
            'dob.date' => 'Please enter a valid date.',
            'dob.before' => 'Date of birth must be in the past.',
            'address.max' => 'Address cannot exceed 255 characters.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Please select a valid gender.',
            'marital_status.required' => 'Marital status is required.',
            'marital_status.in' => 'Please select a valid marital status.',
            'religion.required' => 'Religion is required.',
            'religion.in' => 'Please select a valid religion.',
            'image.required' => 'Profile image is required.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'nid_no.required' => 'NID number is required.',
            'nid_no.digits' => 'NID number must be exactly 10 digits.',
            'nid_front_image.required' => 'Front NID image is required.',
            'nid_front_image.mimes' => 'Front NID image must be a file of type: jpeg, png, jpg, gif.',
            'nid_front_image.max' => 'Front NID image size cannot exceed 2MB.',
            'nid_back_image.required' => 'Back NID image is required.',
            'nid_back_image.mimes' => 'Back NID image must be a file of type: jpeg, png, jpg, gif.',
            'nid_back_image.max' => 'Back NID image size cannot exceed 2MB.',
            'bio.required' => 'A short bio is required',
            'detailed_info.required' => 'Detailed information (Ex: Experience, Education..) is required',
        ];
    }
}
