<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Contracts\Auth\PasswordBroker;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendChangePasswordEmail implements ShouldQueue
{
    use InteractsWithQueue;

    protected $broker;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(PasswordBroker $broker)
    {
        $this->broker = $broker;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        abort_unless($event->user instanceof CanResetPassword, 500);

        $this->broker->sendResetLink([
            $event->user->getAuthIdentifierName() => $event->user->getAuthIdentifier(),
        ]);
    }
}
