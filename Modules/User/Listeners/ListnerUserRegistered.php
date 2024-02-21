<?php

namespace Modules\User\Listeners;

use Modules\User\Events\UserRegistered;
use Modules\User\Services\LogService;
use Modules\User\Services\RequestService;

class ListnerUserRegistered
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserRegistered $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $request = $event->request;
        //send request
        $requestService = new RequestService;
        $requestData = ['user_id' => $request->id, 'type' => $request->type . '_register'];
        $requestLog = $requestService->store(setRequest($requestData));
        //send log
        $logService = new LogService;
        $logData = ['user_id' => $request->id, 'activity' => $request->type . '_register'];
        $log = $logService->store(setRequest($logData));        
        //return $log;
    }
}
