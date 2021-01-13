<?php

namespace Tests\Feature\Livewire\Posts;

use Tests\Feature\TestCase;

class HomepageListTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->get(route('home'))
            ->assertSeeLivewire('posts.homepage-list');
    }
}
