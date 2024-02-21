<?php

namespace Modules\Accounting\Services;

use Illuminate\Http\Request;
use Modules\Accounting\Entities\AccountModule;
use Modules\Accounting\Http\Requests\AccountModuleCreateRequest;
use Modules\Accounting\Http\Requests\AccountModuleUpdateRequest;
use Validator;

class AccountModuleService
{
    public function index(Request $request)
    {
        if (isset($request->page)) {
            $accountModules = AccountModule::select('*')->FilterInput()
                ->paginate($request->per_page, ['*'], 'page', $request->page);
        } else {
            $accountModules = AccountModule::select('*')->FilterInput($request)->get();
        }

        return sendResponse($accountModules, 'AccountModule index successfully', 'plain');
    }

    public function store(Request $request)
    {
        //validate input
        $accountModuleCreateRequest = new AccountModuleCreateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $accountModuleCreateRequest->rules(), $accountModuleCreateRequest->messages());
        if ($validator->fails()) {
            return sendError('AccountModule store error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $accountModule = AccountModule::create($data);
            $accountModule = $accountModule->refresh();
            return sendResponse($accountModule, 'AccountModule store successfully', 'plain');
        }
    }

    public function update(Request $request)
    {
        //if accountModule not exist
        if (!($accountModule = AccountModule::where('id', $request->id)->exists())) {
            return sendError('AccountModule does not exist!', '', '404', 'plain');
        }
        //validate input
        $accountModuleUpdateRequest = new AccountModuleUpdateRequest();
        $data = array_merge($request->all());
        $validator = Validator::make($data, $accountModuleUpdateRequest->rules($data['id']), $accountModuleUpdateRequest->messages());
        if ($validator->fails()) {
            return sendError('AccountModule update error: ' . $validator->errors()->all()[0], '', '404', 'plain');
        } else {
            $accountModule = AccountModule::find($request->id);
            $accountModule->fill($data)->save();
            return sendResponse($accountModule, 'AccountModule update successfully', 'plain');
        }
    }
}
