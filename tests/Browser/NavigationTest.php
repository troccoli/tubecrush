<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class NavigationTest extends DuskTestCase
{
    public function testMainNavBar(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSeeLink('News');
                })->clickLink('Home')
                ->assertRouteIs('home');

            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSeeLink('News');
                })->clickLink('Home')
                ->assertRouteIs('home')
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSeeLink('News');
                })->clickLink('Home')
                ->assertRouteIs('home')
                ->logout();
        });
    }

    public function testDropdownNavigation(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout');
                });

            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSee($this->superAdmin->getName())
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout')
                        ->clickLink($this->superAdmin->getName(), 'button')
                        ->waitFor('@dropdown-menu')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Logout');
                })->clickLink('Dashboard')
                ->assertRouteIs('dashboard')
                ->clickLink($this->superAdmin->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Profile')
                ->assertRouteIs('profile.show')
                ->clickLink($this->superAdmin->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Logout')
                ->assertGuest();

            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSee($this->editor->getName())
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Logout')
                        ->clickLink($this->editor->getName(), 'button')
                        ->waitFor('@dropdown-menu')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Logout');
                })->clickLink('Dashboard')
                ->assertRouteIs('dashboard')
                ->clickLink($this->editor->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Profile')
                ->assertRouteIs('profile.show')
                ->clickLink($this->editor->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Logout')
                ->assertGuest();
        });
    }
}
