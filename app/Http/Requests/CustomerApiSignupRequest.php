<?php

namespace App\Http\Requests;

use App\Services\CustomerAuthService;
use App\Services\ResponseService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class CustomerApiSignupRequest extends FormRequest
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
            'first_name' => ['required_without:token', 'min:2', 'max:60'],
            'last_name' => ['required_without:token', 'min:2', 'max:60'],
            'email' => ['required','unique:customers,email','min:5', 'max:100','regex:/^[a-zA-Z0-9-@._]+$/', 'email'],
            'token' => ['sometimes'],
            'provider' => [
                Rule::requiredIf(!blank(request('token'))),
                Rule::in(CustomerAuthService::SOCIAL_AUTH_PROVIDERS)
            ],
            'password' => ['required_without:token', 'confirmed', 'min:6', 'max:255'],
            'referral_code' => ['sometimes', 'string', 'max:255'],
            'redirect_uri' => ['nullable', 'string', 'max:255'],
            'country_id' => ['required', 'exists:countries,id'],
            'competition_id' => 'nullable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(ResponseService::apiResponse('422','Invalid inputs',   $validator->errors()));
    }
}
