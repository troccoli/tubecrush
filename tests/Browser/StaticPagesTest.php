<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class StaticPagesTest extends DuskTestCase
{
    public function testAboutUsPage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser
                ->visitRoute('about-us')->assertSeeIn('h2', 'About Tube Crush')
                ->visitRoute('guidelines')->assertSeeIn('h2', 'Photo and Comment Guidelines')
                ->visitRoute('legal')->assertSeeIn('h2', 'Legal Information')
                ->visitRoute('photo-removal')->assertSeeIn('h2', 'Photo Removal Request');
        });
    }
}
