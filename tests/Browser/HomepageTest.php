<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    public function testNavigation(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visit('/')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSeeLink('News')
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout');
                })->within('@main-footer', function (Browser $footer): void {
                    $footer->assertSeeLink('About us')
                        ->assertSeeLink('Photo guidelines')
                        ->assertSeeLink('Legal')
                        ->assertSeeLink('Contact us')
                        ->assertSeeLink('Photo removal')
                        ->assertSeeLink('Press enquiries');
                });
            $browser->clickLink('Home')
                ->assertPathIs('/');

            $browser->login()
                ->visit('/')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSeeLink('News')
                        ->assertSee('Super Admin')
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout')
                        ->clickLink('Super Admin', 'button')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Logout');
                })->logout();
        });
    }
}
