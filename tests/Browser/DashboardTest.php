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

    public function testDeletePostAsSuperAdmin(): void
    {
        $this->browse(function (Browser $browser): void {
            /** @var Post $latestPost */
            $latestPost = Post::query()->latest()->first();

            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->scrollAndClick('@delete-post-button');
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

    public function testListPosts(): void
    {
        /*
         * The list should be ordered by creation date, in descending order,
         * i.e. the latest one on top. It should also include draft posts.
         */

        /* This draft post will be top of the list */
        Post::factory()
            ->bySuperAdmin()
            ->withTitle('This is the top post and it is a draft')
            ->now()
            ->draft()
            ->create();
        $topFivePosts = Post::query()->orderByDesc('created_at')->limit(5)->get();

        $this->browse(function (Browser $browser) use ($topFivePosts): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertCountInElement(5, '@post');
                })
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertSeeIn('@post-title', 'This is the top post and it is a draft')
                        ->assertSeeIn('@post-author', $this->superAdmin->getName())
                        ->assertSeeIn('@post-creation-date', now()->toFormattedDateString())
                        ->assertSeeIn('@post-publication-date', 'Draft')
                        ->assertVisible('@edit-post-button')
                        ->assertVisible('@delete-post-button')
                        ->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button');
                })
                ->withEach('@post', function (Browser $row, int $line) use ($topFivePosts): void {
                    $this->assertPost($row, $topFivePosts[$line - 1])
                        ->assertVisible('@delete-post-button');
                })
                ->logout();

            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->within('@posts-list', function (Browser $list): void {
                    $list->assertCountInElement(5, '@post');
                })
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertSeeIn('@post-title', 'This is the top post and it is a draft');
                })
                ->withEach('@post', function (Browser $row, int $line) use ($topFivePosts): void {
                    $this->assertPost($row, $topFivePosts[$line - 1])
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

    public function testPublishPostAsEditor(): void
    {
        $draftPost = Post::factory()
            ->bySuperAdmin()
            ->now()
            ->draft()
            ->create();

        $this->browse(function (Browser $browser) use ($draftPost): void {
            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button')
                        ->scrollAndClick('@publish-post-button');
                })
                ->waitFor('#confirm-publish-post-dialog')
                ->within('#confirm-publish-post-dialog', function (Browser $dialog) use ($draftPost): void {
                    $dialog->assertSee('Are you sure you want to publish the following post?')
                        ->assertSee($draftPost->getTitle())
                        ->assertVisible('@cancel-publish-post-button')
                        ->assertSeeIn('@cancel-publish-post-button', 'WHOOPS, NO THANKS')
                        ->assertVisible('@confirm-publish-post-button')
                        ->assertSeeIn('@confirm-publish-post-button', 'YEP, LET\'S GO');
                })
                ->press('WHOOPS, NO THANKS')
                ->waitUntilMissing('#confirm-publish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button')
                        ->click('@publish-post-button');
                })
                ->waitFor('#confirm-publish-post-dialog')
                ->press('YEP, LET\'S GO')
                ->waitUntilMissing('#confirm-publish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button');
                })->logout();
        });
    }

    public function testPublishPostAsSuperAdmin(): void
    {
        $draftPost = Post::factory()
            ->bySuperAdmin()
            ->now()
            ->draft()
            ->create();

        $this->browse(function (Browser $browser) use ($draftPost): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button')
                        ->scrollAndClick('@publish-post-button');
                })
                ->waitFor('#confirm-publish-post-dialog')
                ->within('#confirm-publish-post-dialog', function (Browser $dialog) use ($draftPost): void {
                    $dialog->assertSee('Are you sure you want to publish the following post?')
                        ->assertSee($draftPost->getTitle())
                        ->assertVisible('@cancel-publish-post-button')
                        ->assertSeeIn('@cancel-publish-post-button', 'WHOOPS, NO THANKS')
                        ->assertVisible('@confirm-publish-post-button')
                        ->assertSeeIn('@confirm-publish-post-button', 'YEP, LET\'S GO');
                })
                ->press('WHOOPS, NO THANKS')
                ->waitUntilMissing('#confirm-publish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button')
                        ->scrollAndClick('@publish-post-button');
                })
                ->waitFor('#confirm-publish-post-dialog')
                ->press('YEP, LET\'S GO')
                ->waitUntilMissing('#confirm-publish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button');
                })->logout();
        });
    }

    public function testRegisteringAUserAsSuperAdmin(): void
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
                ->assertSee('The name field must not be greater than 255 characters.')
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

    public function testUnpublishPostAsEditor(): void
    {
        $post = Post::factory()
            ->bySuperAdmin()
            ->now()
            ->create();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->loginAs($this->editor)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button')
                        ->scrollAndClick('@unpublish-post-button');
                })
                ->waitFor('#confirm-unpublish-post-dialog')
                ->within('#confirm-unpublish-post-dialog', function (Browser $dialog) use ($post): void {
                    $dialog->assertSee('Are you sure you want to unpublish the following post?')
                        ->assertSee($post->getTitle())
                        ->assertVisible('@cancel-unpublish-post-button')
                        ->assertSeeIn('@cancel-unpublish-post-button', 'NAAH, LEAVE IT')
                        ->assertVisible('@confirm-unpublish-post-button')
                        ->assertSeeIn('@confirm-unpublish-post-button', 'OH YEAH');
                })
                ->press('NAAH, LEAVE IT')
                ->waitUntilMissing('#confirm-unpublish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button')
                        ->scrollAndClick('@unpublish-post-button');
                })
                ->waitFor('#confirm-unpublish-post-dialog')
                ->press('OH YEAH')
                ->waitUntilMissing('#confirm-unpublish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button');
                })->logout();
        });
    }

    public function testUnpublishPostAsSuperAdmin(): void
    {
        $post = Post::factory()
            ->bySuperAdmin()
            ->now()
            ->create();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button')
                        ->scrollAndClick('@unpublish-post-button');
                })
                ->waitFor('#confirm-unpublish-post-dialog')
                ->within('#confirm-unpublish-post-dialog', function (Browser $dialog) use ($post): void {
                    $dialog->assertSee('Are you sure you want to unpublish the following post?')
                        ->assertSee($post->getTitle())
                        ->assertVisible('@cancel-unpublish-post-button')
                        ->assertSeeIn('@cancel-unpublish-post-button', 'NAAH, LEAVE IT')
                        ->assertVisible('@confirm-unpublish-post-button')
                        ->assertSeeIn('@confirm-unpublish-post-button', 'OH YEAH');
                })
                ->press('NAAH, LEAVE IT')
                ->waitUntilMissing('#confirm-unpublish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@publish-post-button')
                        ->assertVisible('@unpublish-post-button')
                        ->scrollAndClick('@unpublish-post-button');
                })
                ->waitFor('#confirm-unpublish-post-dialog')
                ->press('OH YEAH')
                ->waitUntilMissing('#confirm-unpublish-post-dialog')
                ->assertRouteIs('posts.list')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertVisible('@publish-post-button')
                        ->assertMissing('@unpublish-post-button');
                })->logout();
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

    private function assertPost(Browser $row, Post $post): Browser
    {
        $row->assertSeeIn('@post-title', $post->getTitle())
            ->assertSeeIn('@post-author', $post->getAuthorName())
            ->assertSeeIn('@post-creation-date', $post->getCreationDate()->toFormattedDateString())
            ->assertVisible('@edit-post-button');

        if ($post->isDraft()) {
            $row->assertSeeIn('@post-publication-date', 'Draft')
                ->assertVisible('@publish-post-button')
                ->assertMissing('@unpublish-post-button');
        } else {
            $row->assertSeeIn('@post-publication-date', $post->getPublishedDate()->toFormattedDateString())
                ->assertMissing('@publish-post-button')
                ->assertVisible('@unpublish-post-button');
        }

        return $row;
    }
}
