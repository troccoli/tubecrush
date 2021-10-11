<?php

namespace Tests\Browser;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class TagsPageTest extends DuskTestCase
{
    private Tag $tag;

    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('posts-by-tags', ['slug' => $this->tag->getSlug()])
                ->within('main', function (Browser $main): void {
                    $main->assertCountInElement(3, '@post')
                        ->press('More posts')
                        ->waitUntilMissing('@loading-icon')
                        ->assertCountInElement(6, '@post');
                });
        });
    }

    public function testContentOfSinglePost(): void
    {
        /** @var Post $post */
        $post = Post::query()->withTag($this->tag->getId())->latest()->first();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->visitRoute('posts-by-tags', ['slug' => $this->tag->getSlug()])
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->assertSeeIn('@photo-credit', $post->getPhotoCredit())
                        ->assertSeeIn('@line', $post->line->getName())
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
        });
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->tag = Tag::query()->inRandomOrder()->first();

        Post::factory()->bySuperAdmin()->count(10)
            ->afterCreating(function (Post $post): void {
                $post->tags()->sync($this->tag);
            })
            ->create();
    }
}
