<?php

namespace Modules\Capital\Http\Controllers\Api;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Capital\Entities\Capital;
use Modules\Capital\Services\CapitalService;
use Modules\Capital\Transformers\CapitalResource;

class CapitalController extends Controller
{
    public function test(Request $request, CapitalService $capitalService)
    {
        $fbUrl = config('capital.account')[1];
        return $fbUrl;
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $capitals = Capital::get();
        //$capital = auth('api')->capital();
        return sendResponse($capitals, 'Capital retrieved successfully.');
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
    public function store(Request $request, CapitalService $capitalService)
    {
        //set capital
        $capital = $capitalService->store($request);
        if (!$capital->success) {
            return sendError($capital->message, '', '404');
        }

        return sendResponse(new CapitalResource($capital->data), 'Capital register successfully.');
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
    public function update(Request $request, CapitalService $capitalService)
    {
        //set capital
        $capital = $capitalService->update($request);
        if (!$capital->success) {
            return sendError($capital->message, '', '404');
        }

        return sendResponse(new CapitalResource($capital->data), 'Update Capital Profile successfully.');
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
