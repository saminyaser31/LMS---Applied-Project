<?php

namespace App\Http\Requests;

use App\Models\AboutUsContents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreAboutUsRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_about_us');
    }

    public function rules()
    {
        $sectionId = $this->input('section_id');
        $aboutUsContents = AboutUsContents::where("section_id", $sectionId)->first();

        $rules = [
            'section_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'required',
                'string',
                'max:20',
            ],
        ];

        // Check if AboutUsContents record exists for this section
        if ($aboutUsContents) {
            $image1Required = $aboutUsContents->image_1 ? 'nullable' : 'required';
            $image2Required = $aboutUsContents->image_2 ? 'nullable' : 'required';
            $image3Required = $aboutUsContents->image_3 ? 'nullable' : 'required';
            $iconImageRequired = $aboutUsContents->icon_image ? 'nullable' : 'required';
        } else {
            // If AboutUsContents doesn't exist, default to 'required'
            $image1Required = 'required';
            $image2Required = 'required';
            $image3Required = 'required';
            $iconImageRequired = 'required';
        }

        switch ($sectionId) {
            case AboutUsContents::ABOUT_DESCRIPTION:
                $rules['section_title'] = ['nullable', 'string', 'max:255'];
                $rules['section_subtitle'] = ['nullable', 'string', 'max:255'];
                $rules['subtitle'] = ['required', 'string', 'max:255'];
                $rules['description'] = ['required', 'string'];
                $rules['image_1'] = [$image1Required, 'file', 'mimes:jpeg,png,gif,bmp,svg,jpg', 'dimensions:min_width=308,min_height=250,max_width=308,max_height=250'];
                $rules['image_2'] = [$image2Required, 'file', 'mimes:jpeg,png,gif,bmp,svg,jpg', 'dimensions:min_width=405,min_height=490,max_width=405,max_height=490'];
                $rules['image_3'] = [$image3Required, 'file', 'mimes:jpeg,png,gif,bmp,svg,jpg', 'dimensions:min_width=396,min_height=530,max_width=396,max_height=530'];
                break;

            case AboutUsContents::APPLICATION_PROCEDURE:
                $rules['section_title'] = ['nullable', 'string', 'max:255'];
                $rules['section_subtitle'] = ['nullable', 'string', 'max:255'];
                $rules['subtitle'] = ['nullable', 'string', 'max:255'];
                $rules['description'] = ['required', 'string', 'max:80'];
                $rules['icon_image'] = [$iconImageRequired, 'file', 'mimes:jpeg,png,gif,bmp,svg,jpg', 'dimensions:min_width=128,min_height=128,max_width=128,max_height=128'];
                break;

            case AboutUsContents::COURSE_FEATURES:
                $rules['section_title'] = ['nullable', 'string', 'max:255'];
                $rules['section_subtitle'] = ['nullable', 'string', 'max:255'];
                $rules['subtitle'] = ['nullable', 'string', 'max:255'];
                $rules['description'] = ['required', 'string', 'max:80'];
                $rules['icon_image'] = [$iconImageRequired, 'file', 'mimes:jpeg,png,gif,bmp,svg,jpg', 'dimensions:min_width=128,min_height=128,max_width=128,max_height=128'];
                break;

            default:
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
