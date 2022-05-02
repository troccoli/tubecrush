<?php

namespace Tests\Feature\Events;

use App\Enums\UserRoles;
use App\Events\NewTubeCrushSubmitted;
use App\Models\Post;
use App\Models\User;
use App\Notifications\NewTubeCrush;
use Illuminate\Support\Facades\Notification;
use Tests\Feature\TestCase;

class NewTubeCrushSubmittedTest extends TestCase
{
    public function testTheEventTriggersAnEmailToAllEditors(): void
    {
        Notification::fake();

        /** @var Post $post */
        $post = Post::factory()->create();
        $editors = User::query()->role(UserRoles::Editor->value)->get();

        NewTubeCrushSubmitted::dispatch($post);

        Notification::assertTimesSent($editors->count(), NewTubeCrush::class);
        $editors->each(fn (User $editor) => Notification::assertSentTo($editor, NewTubeCrush::class));
    }
}
