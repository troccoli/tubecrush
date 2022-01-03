<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    public function testCreatePost(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->click('@create-post-button')
                ->assertRouteIs('posts.create')
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->click('@create-post-button')
                ->assertRouteIs('posts.create')
                ->logout();
        });
    }

    public function testDeletePost(): void
    {
        $this->browse(function (Browser $browser): void {
            /** @var Post $latestPost */
            $latestPost = Post::query()->latest()->first();
            $postCount = Post::query()->count();

            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->within('#confirm-delete-post-dialog', function (Browser $dialog) use ($latestPost): void {
                    $dialog->assertSee('Are you sure you want to delete the following post?')
                        ->assertSee($latestPost->getTitle())
                        ->assertVisible('@cancel-delete-post-button')
                        ->assertSeeIn('@cancel-delete-post-button', 'NEVER MIND')
                        ->assertVisible('@confirm-delete-post-button')
                        ->assertSeeIn('@confirm-delete-post-button', 'YES PLEASE');
                })
                ->press('NEVER MIND')
                ->waitUntilMissing('#confirm-delete-post-dialog')
                ->assertRouteIs('posts.list')
                ->assertSee($latestPost->getTitle())
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->click('@delete-post-button');
                })
                ->waitFor('#confirm-delete-post-dialog')
                ->press('YES PLEASE')
                ->waitUntilMissing('#confirm-delete-post-dialog')
                ->assertRouteIs('posts.list')
                ->assertDontSee($latestPost->getTitle())
                ->logout();
        });
    }

    public function testEditPost(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->click('@edit-post-button');
                })
                ->assertPathBeginsWith('/posts/update/')
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->click('@edit-post-button');
                })
                ->assertPathBeginsWith('/posts/update/')
                ->logout();
        });
    }

    public function testListPostsInCreationDateOrderIncludingDraftPosts(): void
    {
        /* This draft post will be top of the list */
        Post::factory()->draft()->withTitle('This is the top post and it is a draft')->now()->create();
        $topFivePosts = Post::query()->orderByDesc('created_at')->limit(5)->get();

        $this->browse(function (Browser $browser) use ($topFivePosts): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertCountInElement(5, '@post');
                })
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertSeeIn('@post-title', 'This is the top post and it is a draft');
                })
                ->withEach('@post', function (Browser $row, int $line) use ($topFivePosts): void {
                    $post = $topFivePosts[$line - 1];
                    $row->assertSeeIn('@post-title', $post->getTitle())
                        ->assertSeeIn('@post-author', $post->getAuthorName())
                        ->assertSeeIn('@post-creation-date', $post->getCreationDate()->toFormattedDateString())
                        ->assertPresent('@edit-post-button')
                        ->assertPresent('@delete-post-button');
                })
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertCountInElement(5, '@post');
                })
                ->withEach('@post', function (Browser $row, int $line) use ($topFivePosts): void {
                    $post = $topFivePosts[$line - 1];
                    $row->assertSeeIn('@post-title', $post->getTitle())
                        ->assertSeeIn('@post-author', $post->getAuthorName())
                        ->assertSeeIn('@post-creation-date', $post->getCreationDate()->toFormattedDateString())
                        ->assertPresent('@edit-post-button')
                        ->assertMissing('@delete-post-button');
                })
                ->logout();
        });
    }

    public function testNavigation(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('dashboard')
                ->assertRouteIs('login');

            $browser->loginAs($this->superAdmin)
                ->visitRoute('dashboard')
                ->assertSee("{$this->superAdmin->getName()}'s Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertSeeLink('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Users')
                ->assertRouteIs('register')
                ->assertSeeLink('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Posts')
                ->assertRouteIs('posts.list')
                ->assertSeeLink('Users')
                ->assertSeeLink('Posts')
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('dashboard')
                ->assertSee("{$this->editor->getName()}'s Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertDontSeeLink('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Posts')
                ->assertRouteIs('posts.list')
                ->assertDontSeeLink('Users')
                ->assertSeeLink('Posts')
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

    public function testUsersLink(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('dashboard')
                ->assertSeeLink('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Users')
                ->assertRouteIs('register')
                ->clickLink('Posts')
                ->assertRouteIs('posts.list')
                ->assertPresent('@create-post-button')
                ->logout();
            $browser->loginAs($this->editor)
                ->visitRoute('dashboard')
                ->assertSee("{$this->editor->getName()}'s Dashboard")
                ->assertSee('Welcome to your dashboard.')
                ->assertDontSee('Users')
                ->assertSeeLink('Posts')
                ->clickLink('Posts')
                ->assertRouteIs('posts.list')
                ->assertPresent('@create-post-button')
                ->logout();
        });
    }
}
