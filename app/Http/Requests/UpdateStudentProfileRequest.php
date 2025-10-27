<?php

namespace App\Http\Requests;

use App\Models\Student\Students;
use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentProfileRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('student_id');
        $student = Students::select()->where('id', $id)->first();
        $currentImage = ($student && $student->image) ? $student->image : null;

        return [
            'first_name' => [
                'string',
                'required',
                'max:15'
            ],
            'last_name' => [
                'string',
                'required', // Made required since it is marked as required in the form
                'max:15',
            ],
            'email' => [
                'nullable',
                'email',
                'unique:users',
            ],
            'phone_no' => [
                'required',
                'digits:11', // Ensures exactly 11 digits
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
                $currentImage ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,jpg,gif', // Specifies acceptable file types
                'max:2048', // Max file size of 2MB
                'dimensions:min_width=1500,min_height=1500,max_width=1500,max_height=1500',
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
        ];
    }
}
