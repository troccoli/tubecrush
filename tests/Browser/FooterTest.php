<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class FooterTest extends DuskTestCase
{
    public function testLinksInFooter(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home')
                ->clickLink('About us')->assertRouteIs('about-us')
                ->clickLink('Photo guidelines')->assertRouteIs('guidelines')
                ->clickLink('Legal')->assertRouteIs('legal')
                ->clickLink('Photo removal')->assertRouteIs('photo-removal')
                ->clickLink('Press enquiries')->assertRouteIs('press-enquiries')
                ->clickLink('Contact us')->assertRouteIs('contact-us');
        });
    }
}
