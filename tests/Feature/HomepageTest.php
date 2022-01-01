<?php

namespace Tests\Feature;

class HomepageTest extends TestCase
{
    public function testHomepageHasFooterComponent(): void
    {
        $this->get(route('home'))
            ->assertSeeLivewire('footer');
    }
}
