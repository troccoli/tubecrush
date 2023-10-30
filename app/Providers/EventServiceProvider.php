<?php

namespace App\Providers;

use App\Events\NewTubeCrushSubmitted;
use App\Events\SomeoneHasContactedUs;
use App\Listeners\NotifyEditorsNewTubeCrushSubmitted;
use App\Listeners\SendChangePasswordEmail;
use App\Listeners\SendContactUsMessage;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendChangePasswordEmail::class,
        ],
        SomeoneHasContactedUs::class => [
            SendContactUsMessage::class,
        ],
        NewTubeCrushSubmitted::class => [
            NotifyEditorsNewTubeCrushSubmitted::class,
        ],
    ];

    public function boot(): void
    {
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
