<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Line;
use Tests\Feature\TestCase;

class PostsByLineListTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        /** @var Line $line */
        $line = Line::query()->inRandomOrder()->first();

        $this->get(route('posts-by-lines', ['slug' => $line->getSlug()]))
            ->assertSeeLivewire('posts.list-posts');
    }
}
