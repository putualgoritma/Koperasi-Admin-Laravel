<?php

namespace Modules\Accounting\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounting\Services\AccountService;
use Modules\Accounting\Entities\Account;
use Modules\Accounting\Transformers\AccountResource;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, AccountService $accountService)
    {
        //set account
        $account = $accountService->index($request);
        if (!$account->success) {
            return sendError($account->message, '', '404');
        }

        return sendResponse(new AccountResource($account->data), 'Account retrieve successfully.');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, AccountService $accountService)
    {
        //set account
        $account = $accountService->store($request);
        if (!$account->success) {
            return sendError($account->message, '', '404');
        }

        return sendResponse(new AccountResource($account->data), 'Account register successfully.');
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, AccountService $accountService)
    {
        //set account
        $account = $accountService->update($request);
        if (!$account->success) {
            return sendError($account->message, '', '404');
        }

        return sendResponse(new AccountResource($account->data), 'Update Account Profile successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
