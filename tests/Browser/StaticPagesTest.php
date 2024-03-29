<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class StaticPagesTest extends DuskTestCase
{
    public function testStaticPages(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('about-us')->assertSeeIn('h2', 'About Tube Crush')
                ->visitRoute('guidelines')->assertSeeIn('h2', 'Photo and Comment Guidelines')
                ->visitRoute('legal')->assertSeeIn('h2', 'Legal Information')
                ->visitRoute('photo-removal')->assertSeeIn('h2', 'Photo Removal Request')
                ->visitRoute('press-enquiries')->assertSeeIn('h2', 'Press Enquiries')
                ->visitRoute('contact-us')->assertSeeIn('h2', 'Contact us')
                ->visitRoute('send-crush-success')->assertSeeIn('h2', 'Thank you for your TubeCrush');
        });
    }
}
