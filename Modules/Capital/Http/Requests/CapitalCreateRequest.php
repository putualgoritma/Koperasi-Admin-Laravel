<?php

namespace Modules\Capital\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapitalCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required','integer'],
            'ledger_id' => ['integer'],
            'login_id' => ['required','integer'],
            'code' => ['required', 'string', 'max:14', 'unique:capitals,code'],
            'memo' => ['required','string'],
            'type' => ['in:D,C'],
            'amount' => ['required','decimal:0'],
            'register' => ['required', 'date'],
            'period' => ['string'],
            'status' => ['in:pending,active,close'],
            'model' => ['required','string'],    
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
