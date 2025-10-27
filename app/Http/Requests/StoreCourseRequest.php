<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $isTeacherIdRequired = (Auth::user()->user_type == User::ADMIN) ? 'required' : 'nullable';

        return [
            'teacher_id' => [
                $isTeacherIdRequired,
                'integer', // Ensure it exists in the database
            ],
            'course_category' => [
                'required',
                'exists:course_categories,id', // Ensure it exists in the database
            ],
            'subject_id' => [
                'required',
                'exists:course_subjects,id', // Ensure it exists in the database
            ],
            'level_id' => [
                'required',
                'exists:course_levels,id', // Ensure it exists in the database
            ],
            'title' => [
                'required',
                'string',
                'max:50',
            ],
            'short_description' => [
                'required',
                'string',
                'max:250',
            ],
            'long_description' => [
                'required',
                'string',
            ],
            'course_start_date' => [
                'required',
                // 'after:now',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
            // 'content_no' => [
            //     'required',
            //     'array',
            // ],
            // 'content_no.*' => [
            //     'required',
            //     'integer',
            //     'max:150',
            // ],
            // 'content_title' => [
            //     'required',
            //     'array',
            // ],
            // 'content_title.*' => [
            //     'required',
            //     'string',
            //     'max:50',
            // ],
            // 'content_description' => [
            //     'required',
            //     'array',
            // ],
            // 'content_description.*' => [
            //     'required',
            //     'string',
            // ],
            'requirments' => [
                'required',
                'string',
            ],
            'total_class' => [
                'required',
                'integer',
                'min:1',
            ],
            'certificate' => [
                'nullable',
                'boolean',
            ],
            'quizes' => [
                'nullable',
                'boolean',
            ],
            'qa' => [
                'nullable',
                'boolean',
            ],
            'study_tips' => [
                'nullable',
                'boolean',
            ],
            'career_guidance' => [
                'nullable',
                'boolean',
            ],
            'progress_tracking' => [
                'nullable',
                'boolean',
            ],
            'flex_learning_pace' => [
                'nullable',
                'boolean',
            ],
            'price' => [
                'required',
                'numeric',
                'min:0',
            ],
            'discount_type' => [
                'nullable',
                'integer',
                'in:1,2',
                'required_with:discount_amount,discount_start_date,discount_expiry_date', // Required if any of these fields are present
            ],
            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'required_with:discount_type,discount_start_date,discount_expiry_date', // Required if these fields are present
                'sometimes', // Apply this rule only if the discount_type is present
                function ($attribute, $value, $fail) {
                    $price = $this->input('price');
                    if ($this->input('discount_type') == 1 && $value > $price) {
                        return $fail("Discount amount $value cannot be less than the price $price when discount type is FLAT.");
                    }
                    if ($this->input('discount_type') == 2 && $value > 100) {
                        return $fail("Discount amount $value cannot be more than 100 when discount type is PERCENTAGE.");
                    }
                },
            ],
            'discount_start_date' => [
                'nullable',
                // 'after_or_equal:course_start_date',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'before:discount_expiry_date', // Ensure start_date is before expiry_date
                'required_with:discount_type,discount_amount,discount_expiry_date', // Required if these fields are present
            ],
            'discount_expiry_date' => [
                'nullable',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'after:discount_start_date', // Ensure expiry_date is after start_date
                'required_with:discount_type,discount_amount,discount_start_date', // Required if these fields are present
            ],
            'course_card_image' => [
                'file',
                'required',
                'mimes:jpeg,png,gif,bmp,svg,jpg',
                'max:2048',
                'dimensions:min_width=710,min_height=488,max_width=710,max_height=488',
            ],
            'promo_image' => [
                'file',
                'required',
                'mimes:jpeg,png,gif,bmp,svg,jpg',
                'max:2048',
                'dimensions:min_width=710,min_height=488,max_width=710,max_height=488',
            ],
            'promo_video' => [
                'nullable',
                'url',
                'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)(\?.*)?$/', // Validate YouTube URL format
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
