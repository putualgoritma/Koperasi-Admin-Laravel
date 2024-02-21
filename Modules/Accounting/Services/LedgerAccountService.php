<?php

namespace Modules\Accounting\Services;

use Illuminate\Http\Request;
use Modules\Accounting\Entities\LedgerAccount;
use Modules\Accounting\Http\Requests\LedgerAccountCreateRequest;
use Modules\Accounting\Http\Requests\LedgerAccountUpdateRequest;
use Validator;

class LedgerAccountService
{
    public function store(Request $request)
    {
        //validate input
        $ledgerCreateRequest = new LedgerAccountCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $ledgerCreateRequest->rules(), $ledgerCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('LedgerAccount store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $ledger = LedgerAccount::create($data);
            $ledger = $ledger->refresh();
            return sendResponse($ledger, 'LedgerAccount store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if ledger not exist
        if (!($ledger = LedgerAccount::where('id', $request->id)->exists())) {
            return sendError('LedgerAccount does not exist!', '', '404', 'plain');
        }
        //validate input
        $ledgerUpdateRequest = new LedgerAccountUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $ledgerUpdateRequest->rules($data['id']), $ledgerUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('LedgerAccount update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $ledger = LedgerAccount::find($request->id);
            $ledger->fill($data)->save();
            return sendResponse($ledger, 'LedgerAccount update successfully', 'plain');
        }
    }
}
