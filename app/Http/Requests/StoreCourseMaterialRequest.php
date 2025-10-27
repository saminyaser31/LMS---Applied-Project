<?php

namespace App\Http\Requests;

use App\Models\CourseMaterials;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCourseMaterialRequest extends FormRequest
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
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],
            'upload_type' => [
                'required',
                'integer',
                Rule::in([
                    CourseMaterials::TYPE_FILE, CourseMaterials::TYPE_URL,
                ]),
            ],
            'file' => [
                Rule::requiredIf(function () {
                    return $this->upload_type == CourseMaterials::TYPE_FILE;
                }),
                'nullable',
                'file',
                'max:2048',
            ],
            'url' => [
                Rule::requiredIf(function () {
                    return $this->upload_type == CourseMaterials::TYPE_URL;
                }),
                'nullable',
                'string',
                'url',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
