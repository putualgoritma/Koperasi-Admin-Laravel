<?php

namespace Modules\Accounting\Services;

use Illuminate\Http\Request;
use Modules\Accounting\Entities\Ledger;
use Modules\Accounting\Entities\LedgerAccount;
use Modules\Accounting\Http\Requests\LedgerCreateRequest;
use Modules\Accounting\Http\Requests\LedgerUpdateRequest;
use Modules\Accounting\Services\LedgerAccountService;
use Validator;

class LedgerService
{
    public function store(Request $request)
    {
        //check account balance
        if (!$this->balanceAccount($request)) {
            return sendError('Account tidak balance!', '', '404', 'plain');
        }
        //check register
        if (!isset($request->register)) {
            $register = date('Y').'-'.date('m').'-'.date('d');
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
        $ledgerCreateRequest = new LedgerCreateRequest();
        $validator = Validator::make($data, $ledgerCreateRequest->rules(), $ledgerCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Ledger store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $ledger = Ledger::create($data);
            $ledger = $ledger->refresh();
            //store accounts
            $ledgerAccountService = new LedgerAccountService();
            foreach ($request->accounts as $account) {
                $account_merge = array_merge($account, ['ledger_id' => $ledger->id]);
                $requestAccount = setRequest($account_merge);
                $storeLedgerAccount = $ledgerAccountService->store($requestAccount);
                if (!$storeLedgerAccount->success) {
                    return sendError($storeLedgerAccount->message, '', '404', 'plain');
                }
            }
            return sendResponse($ledger, 'Ledger store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if ledger not exist
        if (!($ledger = Ledger::where('id', $request->id)->exists())) {
            return sendError('Ledger does not exist!', '', '404', 'plain');
        }
        //check account balance
        if (!$this->balanceAccount($request)) {
            return sendError('Account tidak balance!', '', '404', 'plain');
        }
        //validate input
        $ledgerUpdateRequest = new LedgerUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $ledgerUpdateRequest->rules($data['id']), $ledgerUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Ledger update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $ledger = Ledger::find($request->id);
            $ledger->fill($data)->save();
            //reset accounts
            LedgerAccount::where('ledger_id', $ledger->id)->delete();
            //store accounts
            $ledgerAccountService = new LedgerAccountService();
            foreach ($request->accounts as $account) {
                $account_merge = array_merge($account, ['ledger_id' => $ledger->id]);
                $requestAccount = setRequest($account_merge);
                $storeLedgerAccount = $ledgerAccountService->store($requestAccount);
                if (!$storeLedgerAccount->success) {
                    return sendError($storeLedgerAccount->message, '', '404', 'plain');
                }
            }
            return sendResponse($ledger, 'Ledger update successfully', 'plain');
        }
    }

    public function balanceAccount(Request $request)
    {
        $balanceStatus = true;
        $amountDebit = 0;
        $amountCredit = 0;
        if (count($request->accounts) > 1) {
            foreach ($request->accounts as $account) {
                if ($account['type'] == 'D') {
                    $amountDebit += $account['amount'];
                } else {
                    $amountCredit += $account['amount'];
                }
            }
            if ($amountDebit != $amountCredit) {
                $balanceStatus = false;
            }
        }
        return $balanceStatus;
    }

    public function codeGenerate($period = '')
    {
        if ($period == '') {
            $period = date('Y') . '-' . date('m');
        }
        $ledger = Ledger::where('register', 'LIKE', $period . '%')->orderBy('code', 'desc')->first();
        if ($ledger && (strlen($ledger->code) == 14)) {
            $last_code = $ledger->code;
        } else {
            $prefix = 'LDG' . str_replace("-", "", $period);
            $last_code = acc_codedef_generate($prefix, 14);
        }
        $code = acc_code_generate($last_code, 14, 9);
        return $code;
    }
}
