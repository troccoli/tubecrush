<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Tag;
use Tests\Feature\TestCase;

class PostsByTagListTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        /** @var Tag $tag */
        $tag = Tag::query()->inRandomOrder()->first();

        $this->get(route('posts-by-tags', ['slug' => $tag->getSlug()]))
            ->assertSeeLivewire('posts.list-posts');
    }
}
