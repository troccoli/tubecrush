<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class FooterTest extends DuskTestCase
{
    public function testLinksInFooter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home')
                ->clickLink('About us')->assertRouteIs('about-us')
                ->clickLink('Photo guidelines')->assertRouteIs('guidelines');
        });
    }
}
