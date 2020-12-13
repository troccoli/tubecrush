<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageTest extends TestCase
{
    public function testHomepageHasNavigationAndFooterComponents(): void
    {
        $this->get('/')
            ->assertSeeLivewire('navigation-dropdown')
            ->assertSeeLivewire('footer');
    }
}
