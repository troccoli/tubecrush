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
                        ->assertSee($this->user()->getName())
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout')
                        ->clickLink($this->user()->getName(), 'button')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Logout');
                })->logout();
        });
    }

    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('main', function (Browser $main): void {
                    $main->assertCountInElement(3, '@post')
                        ->press('More posts')
                        ->pause(1000)
                        ->assertCountInElement(6, '@post');
                });
        });
    }
}
