<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class HomepageTest extends DuskTestCase
{
    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->within('main', function (Browser $main): void {
                    $main->assertCountInElement(3, '@post')
                        ->press('More posts')
                        ->waitUntilMissing('@loading-icon')
                        ->assertCountInElement(6, '@post');
                });
        });
    }

    public function testDraftPostsAreNotShown(): void
    {
        /** @var Post $draftPost */
        $draftPost = Post::factory()->draft()->now()->create();
        $this->browse(function (Browser $browser) use ($draftPost): void {
            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($draftPost): void {
                    $row->assertDontSeeIn('@title', $draftPost->getTitle());
                });
        });
    }

    public function testContentOfSinglePost(): void
    {
        /** @var Post $post */
        $post = Post::query()->latest()->first();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->assertSeeIn('@photo-credit', $post->getPhotoCredit())
                        ->assertSeeIn('@line', $post->getLine()->getName())
                        ->assertSeeIn('@title', $post->getTitle())
                        ->assertSeeIn('@published-date', $post->getPublishedDate()->toFormattedDateString())
                        ->assertSeeIn('@content', $post->getContent())
                        ->within('@tags', function (Browser $tags) use ($post): void {
                            foreach ($post->tags as $tag) {
                                $tags->assertSee(Str::upper($tag->getName()));
                            }
                        })
                        ->assertSeeIn('@likes', trans_choice('post.likes', $post->getLikes()))
                        ->within('@shares', function (Browser $shares): void {
                            $shares->assertVisible('@twitter-share')
                                ->assertVisible('@facebook-share')
                                ->assertVisible('@copy-link-share');
                        });
                });

            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->scrollAndClick('@line')
                        ->assertRouteIs('posts-by-lines', ['slug' => $post->line->getSlug()]);
                });

            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->scrollAndClick('@title')
                        ->assertRouteIs('single-post', ['post' => $post]);
                });

            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $tag = $post->tags->first();
                    $row->scrollAndClick('@tag-' . $tag->getSlug())
                        ->assertRouteIs('posts-by-tags', ['slug' => $tag->getSlug()]);
                });
        });
    }

    public function testDontShowPhotoCreditIfThereIsntOne(): void
    {
        Post::factory()->withoutPhotoCredit()->now()->publishedNow()->create();

        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@photo-credit');
                });
        });
    }

    public function testCommentCountsIsAvailableOnTheHomepage(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $post): void {
                    $post->assertVisible('@comments-count')
                        ->assertSeeIn('@comments-count', '0 Comments');
                });
        });
    }
}
