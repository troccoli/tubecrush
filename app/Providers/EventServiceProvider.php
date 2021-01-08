<?php

namespace App\Providers;

use App\Events\SomeoneHasContactedUs;
use App\Listeners\SendChangePasswordEmail;
use App\Listeners\SendContactUsMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendChangePasswordEmail::class,
        ],
        SomeoneHasContactedUs::class => [
            SendContactUsMessage::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
