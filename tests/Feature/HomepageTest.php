<?php

namespace Tests\Feature;

class HomepageTest extends TestCase
{
    public function testHomepageHasNavigationAndFooterComponents(): void
    {
        $this->get(route('home'))
            ->assertSeeLivewire('navigation-dropdown')
            ->assertSeeLivewire('posts.homepage-list')
            ->assertSeeLivewire('footer');
    }
}
