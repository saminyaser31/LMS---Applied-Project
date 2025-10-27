<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateFaqsRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_faq');
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
            'faq_status' => [
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
