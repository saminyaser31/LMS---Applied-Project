<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateColorRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_color');
    }

    public function rules()
    {
        return [
            'primary_color' => [
                'required',
                'string',
                'max:255',
            ],
            'secondary_color' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
