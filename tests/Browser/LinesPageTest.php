<?php

namespace Tests\Browser;

use App\Models\Line;
use App\Models\Post;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class LinesPageTest extends DuskTestCase
{
    private Line $line;

    public function testListOfPosts(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('posts-by-lines', ['slug' => $this->line->getSlug()])
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
        $draftPost = Post::factory()->draft()->now()->for($this->line, 'line')->create();
        $this->browse(function (Browser $browser) use ($draftPost): void {
            $browser->visitRoute('posts-by-lines', ['slug' => $this->line->getSlug()])
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($draftPost): void {
                    $row->assertDontSeeIn('@title', $draftPost->getTitle());
                });
        });
    }

    public function testContentOfSinglePost(): void
    {
        /** @var Post $post */
        $post = Post::query()->onLine($this->line->getKey())->latest()->first();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->visitRoute('posts-by-lines', ['slug' => $this->line->getSlug()])
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->assertSeeIn('@photo-credit', $post->getPhotoCredit())
                        ->assertSeeIn('@line', $this->line->getName())
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

        $this->line = Line::query()->inRandomOrder()->first();

        Post::factory()->for($this->line, 'line')->count(10)->create();
    }
}
