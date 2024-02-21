<?php

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\User\Events\UserRegistered;
use Modules\User\Listeners\ListnerUserRegistered;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserRegistered::class => [
            ListnerUserRegistered::class,
        ],
    ];

    public function boot()
    {
        Event::listen(
            UserRegistered::class,
            [ListnerUserRegistered::class, 'handle']
        );
     
        Event::listen(function (UserRegistered $event) {
            //
        });
    }
}