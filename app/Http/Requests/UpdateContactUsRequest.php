<?php

namespace App\Http\Requests;

use App\Models\ContactUs;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateContactUsRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_contact_us');
    }

    public function rules()
    {
        $currentFormImage = ContactUs::find(1)->form_image;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],
            'form_title' => [
                'required',
                'string',
                'max:255',
            ],
            'form_subtitle' => [
                'required',
                'string',
                'max:255',
            ],
            'form_image' => [
                $currentFormImage ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,gif,bmp,svg,jpg',
                'dimensions:min_width=500,min_height=550,max_width=500,max_height=550',
            ],
            'phone_no_1' => [
                'required',
                'string',
                'max:11',
                'min:11',
            ],
            'phone_no_2' => [
                'nullable',
                'string',
                'max:11',
                'min:11',
            ],
            'email_1' => [
                'required',
                'email',
            ],
            'email_2' => [
                'nullable',
                'email',
            ],
            'location_1' => [
                'required',
                'string',
            ],
            'location_2' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function messages()
    {
        return [];
    }
}
