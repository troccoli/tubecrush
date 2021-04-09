<?php

namespace Tests\Browser;

use App\Models\Line;
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
                        ->assertSee('Lines');
                })->clickLink('Home')
                ->assertRouteIs('home');

            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSee('Lines');
                })->clickLink('Home')
                ->assertRouteIs('home')
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSeeLink('Home')
                        ->assertSee('Lines');
                })->clickLink('Home')
                ->assertRouteIs('home')
                ->logout();
        });
    }

    public function testLinesDropdownNavigation(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home');
            $this->assertLinesDropdownNavigation($browser);

            $browser->loginAs($this->superAdmin)
                ->visitRoute('home');
            $this->assertLinesDropdownNavigation($browser);
            $browser->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('home');
            $this->assertLinesDropdownNavigation($browser);
            $browser->logout();
        });
    }

    private function assertLinesDropdownNavigation(Browser $browser): void
    {
        foreach (Line::all() as $line) {
            $browser
                ->within('@main-nav', function (Browser $nav) use ($line): void {
                    $nav->assertDontSeeLink($line->getName())
                        ->clickLink('Lines', 'button')
                        ->waitFor("@{$line->getSlug()}-link")
                        ->assertSeeLink($line->getName())
                        ->clickLink($line->getName())
                        ->assertRouteIs('posts-by-lines', ['slug' => $line->getSlug()]);
                });
        }
    }

    public function testDropdownNavigation(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertDontSeeLink('Create Post')
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Log out');
                });

            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSee($this->superAdmin->getName())
                        ->assertDontSeeLink('Create post')
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Log out')
                        ->clickLink($this->superAdmin->getName(), 'button')
                        ->waitFor('@dropdown-menu')
                        ->assertSeeLink('Create post')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Log out');
                })
                ->clickLink('Create post')
                ->assertRouteIs('posts.create')
                ->clickLink($this->superAdmin->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Dashboard')
                ->assertRouteIs('dashboard')
                ->clickLink($this->superAdmin->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Profile')
                ->assertRouteIs('profile.show')
                ->clickLink($this->superAdmin->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Log out')
                ->assertGuest();

            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->assertSee($this->editor->getName())
                        ->assertDontSeeLink('Create post')
                        ->assertDontSeeLink('Dashboard')
                        ->assertDontSeeLink('Profile')
                        ->assertDontSeeLink('Log out')
                        ->clickLink($this->editor->getName(), 'button')
                        ->waitFor('@dropdown-menu')
                        ->assertSeeLink('Create post')
                        ->assertSeeLink('Dashboard')
                        ->assertSeeLink('Profile')
                        ->assertSeeLink('Log out');
                })
                ->clickLink('Create post')
                ->assertRouteIs('posts.create')
                ->clickLink($this->editor->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Dashboard')
                ->assertRouteIs('dashboard')
                ->clickLink($this->editor->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Profile')
                ->assertRouteIs('profile.show')
                ->clickLink($this->editor->getName(), 'button')
                ->waitFor('@dropdown-menu')
                ->clickLink('Log out')
                ->assertGuest();
        });
    }
}
