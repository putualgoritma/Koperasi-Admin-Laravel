<?php

namespace Modules\Accounting\Services;

use Illuminate\Http\Request;
use Modules\Accounting\Entities\Account;
use Modules\Accounting\Http\Requests\AccountCreateRequest;
use Modules\Accounting\Http\Requests\AccountUpdateRequest;
use Validator;

class AccountService
{
    public function index(Request $request)
    {
        if (isset($request->page)) {
            $accounts = Account::select('*')->FilterInput($request)->SetOrderBy($request)
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $accounts = Account::select('*')->FilterInput($request)->SetOrderBy($request)->get();
        }

        return sendResponse($accounts, 'Account index successfully', 'plain');
    }

    public function store(Request $request)
    {
        //check code
        if (!isset($request->code)) {
            $code = $this->codeGenerate($request);
            $request->request->add(['code' => $code]);
        }
        //validate input
        $accountCreateRequest = new AccountCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $accountCreateRequest->rules(), $accountCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('Account store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $account = Account::create($data);
            $account = $account->refresh();
            return sendResponse($account, 'Account store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if account not exist
        if (!($account = Account::where('id', $request->id)->exists())) {
            return sendError('Account does not exist!', '', '404', 'plain');
        }
        //validate input
        $accountUpdateRequest = new AccountUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $accountUpdateRequest->rules($data['id']), $accountUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('Account update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $account = Account::find($request->id);
            $account->fill($data)->save();
            return sendResponse($account, 'Account update successfully', 'plain');
        }
    }

    public function codeGenerate(Request $request)
    {
        //check if has parent
        if ($request->parent_id != null) {
            //get parent code
            $parentRequestArr = ['parent_id' => $request->parent_id];
            $parentRequest = setRequest($parentRequestArr);
            $parent = $this->index($parentRequest);
            if (!$parent->success) {
                return sendError($parent->message, '', '404', 'plain');
            }
            if (!isset($parent->data) || strlen($parent->data->code) !=8) {
                return sendError('Kode kosong!', '', '404', 'plain');
            }
            $codeParent = $parent->data->code;
            //get last sibling code
            $siblingRequestArr = ['order_by' => 'code', 'order_by_dir' => 'DESC'];
            $siblingRequest = setRequest($siblingRequestArr);
            $sibling = $this->index($siblingRequest);
            if (!$sibling->success) {
                return sendError($sibling->message, '', '404', 'plain');
            }
            if (!isset($sibling->data) || strlen($sibling->data->code) !=8) {
                return sendError('Kode kosong!', '', '404', 'plain');
            }

            $codeBrother = '1-010108';
            $codeParentRtrim = rtrim($codeParent, "0");
            $codeParentRtrimLength = strlen($codeParentRtrim);
            if ($codeParentRtrimLength <= 6) {
                $codeBrotherLast = substr($codeBrother, $codeParentRtrimLength);
                $codeNew = $codeBrotherLast + 1;
                $codeNew = $codeParentRtrim . str_pad($codeNew, 2, 0, STR_PAD_LEFT);
                echo $codeNew;
            } else {
                echo 'parent max 3 tier';
            }
        } else {

        }
        return $code;
    }
}
