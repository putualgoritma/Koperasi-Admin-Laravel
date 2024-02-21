<?php

namespace Modules\Accounting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LedgerUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules($id = null)
    {
        return [
            'code' => ['max:14', 'unique:ledgers,code,' . $id],
            'parent_id' => ['integer'],
            'user_id' => ['integer'],
            'login_id' => ['integer'],
            'title' => ['string'],
            'status' => ['in:pending,active,close'],
            'register' => ['date'],
            'accounts' => ['array'],
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
