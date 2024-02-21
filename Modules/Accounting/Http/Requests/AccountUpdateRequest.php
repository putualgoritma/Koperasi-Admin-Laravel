<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id = null)
    {
        return [
            'code' => ['max:7', 'unique:accounts,code,' . $id],
            'name' => [],
            'type' => ['in:Assets,Liabilities,Equity,Revenues,Expenses'],
            'postable' => ['in:Y,N'],            
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
