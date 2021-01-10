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

    public function __construct(PasswordBroker $broker)
    {
        $this->broker = $broker;
    }

    public function handle(Registered $event): void
    {
        abort_unless($event->user instanceof CanResetPassword, 500);

        $this->broker->sendResetLink([
            $event->user->getAuthIdentifierName() => $event->user->getAuthIdentifier(),
        ]);
    }
}
