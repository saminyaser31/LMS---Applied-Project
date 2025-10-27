<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCourseContentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'course_id' => [
                'required',
                'exists:courses,id', // Ensure it exists in the database
            ],
            'content_no' => [
                'required',
                'integer',
                'max:150',
            ],
            'content_title' => [
                'required',
                'string',
                'max:50',
            ],
            'content_description' => [
                'required',
                'string',
            ],
            'class_time' => [
                'nullable',
                // 'after:now',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            'class_link' => [
                'nullable',
                'string',
                'url',
            ],
            'content_status' => [
                'required',
                'integer',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
