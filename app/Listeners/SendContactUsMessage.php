<?php

namespace App\Listeners;

use App\Events\SomeoneHasContactedUs;
use App\Notifications\ContactUsMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class SendContactUsMessage implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(SomeoneHasContactedUs $event): void
    {
        $SuperAdmin = Role::query()->whereName('Super Admin')->with(['users'])->first()->users;

        foreach ($SuperAdmin as $superAdmin) {
            $superAdmin->notify(new ContactUsMessage($event->getName(), $event->getEmail(), $event->getMessage()));
        }
    }
}
