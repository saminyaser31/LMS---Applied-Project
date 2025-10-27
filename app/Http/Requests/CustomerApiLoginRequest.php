<?php

namespace App\Http\Requests;

use App\Services\CustomerAuthService;
use App\Services\ResponseService;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerApiLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['sometimes', 'min:5', 'max:100', 'regex:/^[a-zA-Z0-9-@._]+$/', 'email'],
            'token' => [Rule::requiredIf(blank(request('email')))],
            'provider' => [
                Rule::requiredIf(!blank(request('token'))),
                Rule::in(CustomerAuthService::SOCIAL_AUTH_PROVIDERS)
            ],
            'password' => [Rule::requiredIf(!blank(request('email'))), 'min:6', 'max:255'],
            'redirect_uri' => ['nullable', 'string', 'max:255']
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseService::apiResponse('422', 'Invalid inputs',   $validator->errors()));

    }
}
