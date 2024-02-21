<?php

namespace Modules\Accounting\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Accounting\Entities\Ledger;
use Modules\Accounting\Services\LedgerService;
use Modules\Accounting\Transformers\LedgerResource;

class LedgerController extends Controller
{
    public function test(Request $request, LedgerService $ledgerService)
    {
        $code = $ledgerService->codeGenerate();
        return $code;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $ledgers = Ledger::get();
        //$ledger = auth('api')->ledger();
        return sendResponse($ledgers, 'Ledger retrieved successfully.');
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
    public function store(Request $request, LedgerService $ledgerService)
    {
        //set ledger
        $ledger = $ledgerService->store($request);
        if (!$ledger->success) {
            return sendError($ledger->message, '', '404');
        }

        return sendResponse(new LedgerResource($ledger->data), 'Ledger register successfully.');
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
    public function update(Request $request, LedgerService $ledgerService)
    {
        //set ledger
        $ledger = $ledgerService->update($request);
        if (!$ledger->success) {
            return sendError($ledger->message, '', '404');
        }

        return sendResponse(new LedgerResource($ledger->data), 'Update Ledger Profile successfully.');
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
