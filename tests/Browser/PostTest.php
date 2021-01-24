<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    public function testCreatingAPost(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->login()
                ->visitRoute('posts.list')
                ->click('@create-post-button')
                ->assertRouteIs('posts.create')
                ->type('#title', 'My new post')
                ->type('#content', 'Awesome content for my new posts')
                ->press('CREATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->within('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->assertSee('My new post')
                        ->assertSee($this->user()->getName());
                })
                ->click('@create-post-button')
                ->type('#title', 'My second post')
                ->type('#content', 'More content for my second post')
                ->press('CANCEL')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertDontSee('My second post');
                })
                ->within('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->assertSee('My new post')
                        ->assertSee($this->user()->getName());
                });
        });
    }
}
