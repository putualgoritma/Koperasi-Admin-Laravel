<?php

namespace Modules\Capital\Services;

use Illuminate\Http\Request;
use Modules\Accounting\Services\AccountModuleService;
use Modules\Accounting\Services\LedgerService;
use Modules\Capital\Entities\Capital;
use Modules\Capital\Http\Requests\CapitalCreateRequest;
use Modules\Capital\Http\Requests\CapitalUpdateRequest;
use Validator;

class CapitalService
{
    public function store(Request $request)
    {
        //check register
        if (!isset($request->register)) {
            $register = date('Y') . '-' . date('m') . '-' . date('d');
            $request->request->add(['register' => $register]);
        }
        //check code
        if (!isset($request->code)) {
            $period = substr($request->register, 0, 7);
            $code = $this->codeGenerate($period);
            $request->request->add(['code' => $code]);
        }
        //check login_id
        if (!isset($request->login_id)) {
            $authUser = auth('api')->user();
            $request->request->add(['login_id' => $authUser->id]);
        }
        $data = array_merge($request->all());
        //validate input
        $capitalCreateRequest = new CapitalCreateRequest();
        $validator = Validator::make($data, $capitalCreateRequest->rules(), $capitalCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Capital store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $capital = Capital::create($data);
            //store ledger
            $storeLedger = $this->capitalLedger($request);
            if (!$storeLedger->success) {
                return sendError($storeLedger->message, '', '404', 'plain');
            }
            //return sendResponse($storeLedger->data, 'testttt', 'plain');
            $capital->ledger_id = $storeLedger->data->id;
            $capital->save();
            $capital = $capital->refresh();
            return sendResponse($capital, 'Capital store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if capital not exist
        if (!($capital = Capital::where('id', $request->id)->exists())) {
            return sendError('Capital does not exist!', '', '404', 'plain');
        }
        //validate input
        $capitalUpdateRequest = new CapitalUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $capitalUpdateRequest->rules($data['id']), $capitalUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Capital update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $capital = Capital::find($request->id);
            $capital->fill($data)->save();
            //update ledger
            $updateLedger = $this->capitalLedger($request, 'update');
            if (!$updateLedger->success) {
                return sendError($updateLedger->message, '', '404', 'plain');
            }
            return sendResponse($capital, 'Capital update successfully', 'plain');
        }
    }

    public function codeGenerate($period = '')
    {
        if ($period == '') {
            $period = date('Y') . '-' . date('m');
        }
        $capital = Capital::where('register', 'LIKE', $period . '%')->orderBy('code', 'desc')->first();
        if ($capital && (strlen($capital->code) == 14)) {
            $last_code = $capital->code;
        } else {
            $prefix = 'CAP' . str_replace("-", "", $period);
            $last_code = acc_codedef_generate($prefix, 14);
        }
        $code = acc_code_generate($last_code, 14, 9);
        return $code;
    }

    public function capitalLedger(Request $request, $type = 'store')
    {
        //set ledger request
        $request->request->add(['title' => $request->memo]);
        $accountsArr[] = [
            'account_id' => $request->account_debit_id,
            'amount' => $request->amount,
            'type' => 'D',
        ];
        //get account module
        $accountModuleService = new AccountModuleService();
        $accountModuleRequest = ['module' => config('capital.nameLower'), 'code' => $request->model];
        $accountModuleRequest = setRequest($accountModuleRequest);
        $accountModule = $accountModuleService->index($accountModuleRequest);
        if (!$accountModule->success) {
            return sendError($accountModule->message, '', '404', 'plain');
        }
        //return sendResponse($accountModule->data[0], 'testttttt', 'plain');
        $accountsArr[] = [
            'account_id' => $accountModule->data[0]->account_id,
            'amount' => $request->amount,
            'type' => $accountModule->data[0]->type,
        ];
        //store ledger
        $request->request->add(['accounts' => $accountsArr]);
        $request->request->remove('code');
        $ledgerService = new LedgerService();
        if ($type == 'store') {
            $storeLedger = $ledgerService->store($request);
        } else {
            $storeLedger = $ledgerService->update($request);
        }
        if (!$storeLedger->success) {
            return sendError($storeLedger->message, '', '404', 'plain');
        }
        return sendResponse($storeLedger->data, 'Capital store successfully', 'plain');
    }
}
