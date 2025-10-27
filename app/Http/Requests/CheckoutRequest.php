<?php

namespace App\Http\Requests;

use App\Models\Order;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'first_name' => [
                'required',
                'string',
                'max:255',
            ],
            'last_name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'phone' => [
                'required',
                'string',
                'max:11',
            ],
            'course_id' => [
                'required',
                'array',
            ],
            'course_id.*' => [
                'required',
                'integer',
                'exists:courses,id',
            ],
            'transaction_id' => [
                'required',
                'nullable',
                'string',
                'max:150',
            ],
            'bkash_no' => [
                'required',
                'string',
                'max:4',
            ],
            'total' => [
                'required',
                'numeric',
            ],
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'You need to select at least one course!',
        ];
    }
}
