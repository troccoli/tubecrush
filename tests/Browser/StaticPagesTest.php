<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StaticPagesTest extends DuskTestCase
{
    public function testAboutUsPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('about-us')
                ->assertSeeIn('h2', 'About Tube Crush');
        });
    }
}
