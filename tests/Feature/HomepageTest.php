<?php

namespace Tests\Feature;

class HomepageTest extends TestCase
{
    public function testHomepageHasNavigationAndFooterComponents(): void
    {
        $this->get('/')
            ->assertSeeLivewire('navigation-dropdown')
            ->assertSeeLivewire('footer');
    }
}
