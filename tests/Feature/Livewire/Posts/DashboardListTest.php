<?php

namespace Tests\Feature\Livewire\Posts;

use Tests\Feature\TestCase;

class DashboardListTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('posts'))
            ->assertSeeLivewire('posts.dashboard-list');
    }
}
