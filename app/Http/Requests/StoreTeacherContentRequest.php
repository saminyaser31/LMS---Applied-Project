<?php

namespace App\Http\Requests;

use App\Models\TeacherContents;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreTeacherContentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'teacher_id' => [
                Rule::requiredIf(function () {
                    return Auth::user()->user_type == User::ADMIN;
                }),
                'nullable',
            ],
            'content_type' => [
                'required',
                'integer',
            ],
            'upload_type' => [
                'required',
                'integer',
                Rule::in([
                    TeacherContents::TYPE_FILE, TeacherContents::TYPE_URL,
                ]),
            ],
            'file' => [
                Rule::requiredIf(function () {
                    return $this->upload_type == TeacherContents::TYPE_FILE;
                }),
                'nullable',
                'file',
                'max:2048',
            ],
            'url' => [
                Rule::requiredIf(function () {
                    return $this->upload_type == TeacherContents::TYPE_URL;
                }),
                'nullable',
                'string',
                'url',
            ],
            'thumbnail' => [
                'required',
                'file',
                'max:2048',
                'dimensions:min_width=650,min_height=650,max_width=650,max_height=650',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
