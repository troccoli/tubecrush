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

    public function testUpdatingAPost(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for($this->user(), 'author')->create(['created_at' => Carbon::now()]);

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->login()
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->click('@edit-post-button');
                })
                ->assertRouteIs('posts.update', ['postId' => $post->getId()])
                ->assertValue('#title', $post->getTitle())
                ->assertValue('#content', $post->getContent())
                ->type('#title', 'My new post')
                ->type('#content', 'Awesome content for my new post')
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->within('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->assertSee('My new post')
                        ->assertSee($this->user()->getName());
                })
                ->with('[dusk="post"]:first-child', function (Browser $list): void {
                    $list->click('@edit-post-button');
                })
                ->assertValue('#title', 'My new post')
                ->assertValue('#content', 'Awesome content for my new post')
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

    public function testDeletingAPost(): void
    {
        /** @var Post $post */
        $post = Post::query()->latest()->first();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->login()
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $postRow): void {
                    $postRow->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->within('#confirm-delete-post-dialog', function (Browser $dialog): void {
                    $dialog->assertSeeIn('@cancel-delete-post-button', 'NEVERMIND')
                        ->assertSeeIn('@confirm-delete-post-button', 'YES PLEASE')
                        ->click('@cancel-delete-post-button');
                })
                ->waitUntilMissing('#confirm-delete-post-dialog')
                ->within('@posts-list', function (Browser $list) use ($post): void {
                    $list->assertSee($post->getTitle());
                })
                ->with('[dusk="post"]:first-child', function (Browser $postRow): void {
                    $postRow->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->within('#confirm-delete-post-dialog', function (Browser $dialog): void {
                    $dialog->click('@cancel-delete-post-button');
                })
                ->waitUntilMissing('#confirm-delete-post-dialog')
                ->within('@posts-list', function (Browser $list) use ($post): void {
                    $list->assertSee($post->getTitle());
                })
                ->with('[dusk="post"]:first-child', function (Browser $postRow): void {
                    $postRow->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->within('#confirm-delete-post-dialog', function (Browser $dialog): void {
                    $dialog->click('@confirm-delete-post-button');
                })
                ->waitUntilMissing('#confirm-delete-post-dialog')
                ->within('@posts-list', function (Browser $list) use ($post): void {
                    $list->assertDontSee($post->getTitle());
                });
        });
    }
}
