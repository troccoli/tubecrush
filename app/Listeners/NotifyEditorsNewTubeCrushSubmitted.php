<?php

namespace App\Listeners;

use App\Enums\UserRoles;
use App\Events\NewTubeCrushSubmitted;
use App\Models\User;
use App\Notifications\NewTubeCrush;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Permission\Models\Role;

class NotifyEditorsNewTubeCrushSubmitted implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(NewTubeCrushSubmitted $event): void
    {
        $notification = new NewTubeCrush($event->post);

        Role::query()->whereName(UserRoles::Editor->value)->with(['users'])->first()->users
            ->each(fn (User $user) => $user->notify($notification));
    }
}
