<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePrivacyPolicyRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_privacy_policy');
    }

    public function rules()
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'required',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
