<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Modules\User\Entities\Request as Requestlog;

class RequestService
{
    public function store(Request $request)
    {
        //validate input
        $data = array_merge($request->all());
        $request = Requestlog::create($data);
        return sendResponse($request, 'Request store successfully', 'plain');
    }

    public function update(Request $request)
    {
        //if request not exist
        if (!($request = Requestlog::where('id', $request->id)->exists())) {
            return sendError('Request does not exist!', '', '404', 'plain');
        }
        //validate input
        $data = array_merge($request->all());
        return sendResponse($request, 'Request update successfully', 'plain');
    }
}
