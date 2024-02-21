<?php

namespace Modules\Capital\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapitalUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id = null)
    {
        return [
            'user_id' => ['integer'],
            'ledger_id' => ['integer'],
            'login_id' => ['integer'],
            'code' => [ 'string', 'max:14', 'unique:capitals,code,' . $id],
            'memo' => ['string'],
            'type' => ['in:D,C'],
            'amount' => ['decimal','decimal:0'],
            'register' => [ 'date'],
            'period' => ['string'],
            'status' => ['in:pending,active,close'],
            'model' => ['string'],
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
