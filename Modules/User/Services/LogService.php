<?php

namespace Modules\User\Services;

use Illuminate\Http\Request;
use Modules\User\Entities\Log;

class LogService
{
    public function store(Request $request)
    {
        //validate input
        $data = array_merge($request->all());
        $log = Log::create($data);
        return sendResponse($log, 'Log store successfully', 'plain');
    }

    public function update(Request $request)
    {
        //if log not exist
        if (!($log = Log::where('id', $request->id)->exists())) {
            return sendError('Log does not exist!', '', '404', 'plain');
        }
        //validate input
        $data = array_merge($request->all());
        return sendResponse($log, 'Log update successfully', 'plain');
    }
}
