<?php

namespace Modules\Accounting\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\AccountModule;
use Modules\Accounting\Services\AccountModuleService;
use Modules\Accounting\Transformers\AccountModuleResource;

class AccountModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(Request $request, AccountModuleService $accountModuleService)
    {
        //set accountModule
        $accountModule = $accountModuleService->index($request);
        if (!$accountModule->success) {
            return sendError($accountModule->message, '', '404');
        }

        return sendResponse(new AccountModuleResource($accountModule->data), 'AccountModule register successfully.');
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
    public function store(Request $request, AccountModuleService $accountModuleService)
    {
        //set accountModule
        $accountModule = $accountModuleService->store($request);
        if (!$accountModule->success) {
            return sendError($accountModule->message, '', '404');
        }

        return sendResponse(new AccountModuleResource($accountModule->data), 'AccountModule register successfully.');
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
    public function update(Request $request, AccountModuleService $accountModuleService)
    {
        //set accountModule
        $accountModule = $accountModuleService->update($request);
        if (!$accountModule->success) {
            return sendError($accountModule->message, '', '404');
        }

        return sendResponse(new AccountModuleResource($accountModule->data), 'Update AccountModule Profile successfully.');
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
