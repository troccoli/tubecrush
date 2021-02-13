<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('main', function (Browser $main): void {
                    $main->assertCountInElement(3, '@post')
                        ->press('More posts')
                        ->waitUntilMissing('@loading-icon')
                        ->assertCountInElement(6, '@post');
                });
        });
    }
}
