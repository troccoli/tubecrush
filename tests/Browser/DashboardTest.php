<?php

namespace Tests\Browser;

use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    public function testDashboard(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('dashboard')
                ->assertSee("{$this->superAdmin->getName()}'s Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertSeeLink('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Users')
                ->assertRouteIs('register')
                ->clickLink('Posts')
                ->assertRouteIs('posts')
                ->withEach('@post', function (Browser $post): void {
                    $post->assertPresent('@edit-post')
                        ->assertPresent('@delete-post');
                })
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('dashboard')
                ->assertSee("{$this->editor->getName()}'s Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertDontSee('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Posts')
                ->assertRouteIs('posts')
                ->withEach('@post', function (Browser $post): void {
                    $post->assertPresent('@edit-post')
                        ->assertMissing('@delete-post');
                })
                ->logout();
        });
    }

    public function testRegisteringAUser(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('dashboard')
                ->clickLink('Users')
                ->assertSee('Name')
                ->assertSee('Email')
                ->assertButtonEnabled('REGISTER')

                // Name and email are mandatory
                ->clear('#name')
                ->clear('#email')
                ->pressAndWaitFor('REGISTER')
                ->assertSee('The name field is required.')
                ->assertSee('The email field is required.')
                ->assertDontSee('The user has been registered.')

                // Name must not be longer than 255 characters
                ->type('#name', Str::random(256))
                ->pressAndWaitFor('REGISTER')
                ->assertSee('The name may not be greater than 255 characters.')
                ->assertDontSee('The user has been registered.')

                // The email must not have been used before
                ->type('#email', 'editor@example.com')
                ->pressAndWaitFor('REGISTER')
                ->assertSee('The email has already been taken.')
                ->assertDontSee('The user has been registered.')

                // Green journey
                ->type('#name', 'John')
                ->type('#email', 'john@example.com')
                ->pressAndWaitFor('REGISTER')
                ->assertSee('The user has been registered.')
                ->logout();
        });
    }

    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertCountInElement(5, '@post');
                });
        });
    }
}
