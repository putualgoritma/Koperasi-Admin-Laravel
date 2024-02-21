<?php

namespace Modules\User\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class ContactUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        return [
            'name' => ['max:50'],
            'email' => ['email'],
            'phone' => ['regex:/^([0-9\s\-\+\(\)]*)$/', 'min:10'],
            'sex' => ['in:M,F'],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            'success' => false, 'message' => 'Contact create error.', "data" => $validator->getMessageBag(),
        ], 400));
    }

    public function messages()
    {
        return [];
    }
}
