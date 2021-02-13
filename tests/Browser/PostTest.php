<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Support\Carbon;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    public function testCreatingAPost(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->superAdmin->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->click('@create-post-button')
                ->assertRouteIs('posts.create')
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->editor->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->click('@create-post-button')
                ->assertRouteIs('posts.create')
                ->logout();
        });
    }

    public function testUpdatingAPost(): void
    {
        /** @var Post $latestPost */
        $latestPost = Post::query()->latest()->first();

        $this->browse(function (Browser $browser) use ($latestPost): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->superAdmin->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->with('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->click('@edit-post-button');
                })
                ->assertRouteIs('posts.update', ['postId' => $latestPost->getId()])
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->editor->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->with('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->click('@edit-post-button');
                })
                ->assertRouteIs('posts.update', ['postId' => $latestPost->getId()])
                ->logout();
        });
    }

    public function testDeletingAPost(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->superAdmin->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->with('[dusk="post"]:first-child', function (Browser $postRow): void {
                    $postRow->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->assertSee('Are you sure you want to delete the following post?')
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('home')
                ->within('@main-nav', function (Browser $nav): void {
                    $nav->clickLink($this->editor->getName(), 'button')
                        ->waitForLink('Dashboard')
                        ->clickLink('Dashboard');
                })
                ->clickLink('Posts')
                ->with('[dusk="post"]:first-child', function (Browser $postRow): void {
                    $postRow->assertDontSeeLink('@delete-post-button');
                })
                ->logout();
        });
    }
}
