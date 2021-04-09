<?php

namespace Tests\Browser;

use App\Models\Line;
use App\Models\Post;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LinesPageTest extends DuskTestCase
{
    private Line $line;

    protected function setUp(): void
    {
        parent::setUp();

        $this->line = Line::query()->inRandomOrder()->first();

        Post::factory()->bySuperAdmin()->for($this->line, 'line')->count(10)->create();
    }

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

    public function testContentOfSinglePost(): void
    {
        /** @var Post $post */
        $post = Post::query()->onLine($this->line->getId())->latest()->first();

        $this->browse(function (Browser $browser) use ($post): void {
            $browser->visitRoute('posts-by-lines', ['slug' => $this->line->getSlug()])
                ->with('[dusk="post"]:first-child', function (Browser $row) use ($post): void {
                    $row->assertSeeIn('@photo-credit', $post->getPhotoCredit())
                        ->assertSeeIn('@line', $this->line->getName())
                        ->assertSeeIn('@title', $post->getTitle())
                        ->assertSeeIn('@content', $post->getContent())
                        ->assertSeeIn('@author-with-date',
                            $post->getAuthorName().' '.$post->getPublishedDate()->toFormattedDateString())
                        ->within('@tags', function (Browser $tags) use ($post): void {
                            foreach ($post->tags as $tag) {
                                $tags->assertSee(Str::upper($tag->getName()));
                            }
                        });
                });
        });
    }
}