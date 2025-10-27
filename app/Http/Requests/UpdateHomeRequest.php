<?php

namespace App\Http\Requests;

use App\Models\HomeContents;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateHomeRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('edit_home');
    }

    public function rules()
    {
        $sectionId = $this->input('section_id');
        $homeContents = HomeContents::where("section_id", $sectionId)->first();
        $isDescriptionrequired = ($sectionId == HomeContents::HERO_SECTION) ? 'required' : 'nullable';

        $currentImage = ($homeContents && $homeContents->image) ? $homeContents->image : null;

        $rules = [
            'section_id' => [
                'required',
                'integer',
            ],
            'title' => [
                'required',
                'string',
                // 'max:255',
            ],
            'subtitle' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                $isDescriptionrequired,
                'string',
                'max:400',
            ],
            'bg_image' => [
                // $currentImage ? 'nullable' : 'required',
                'nullable',
                'file',
                'mimes:jpeg,png,gif,bmp,svg,jpg',
                'dimensions:min_width=1920,min_height=1408,max_width=1920,max_height=1408',
            ],
        ];

        return $rules;
    }

    public function messages()
    {
        return [];
    }
}
