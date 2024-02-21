<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LedgerCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'max:14', 'unique:ledgers,code'],
            'parent_id' => ['integer'],
            'user_id' => ['integer'],
            'login_id' => ['required','integer'],
            'title' => ['required','string'],
            'status' => ['in:pending,active,close'],   
            'register' => ['required', 'date'],  
            'accounts' => ['required', 'array'],       
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
}
