<?php

namespace Tests\Browser;

use App\Models\Post;
use Illuminate\Support\Str;
use Laravel\Dusk\Browser;
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
                        ->assertSeeIn('@content', $post->getContent())
                        ->assertSeeIn('@author-with-date',
                            $post->getAuthorName().' '.$post->getPublishedDate()->toFormattedDateString())
                        ->within('@tags', function (Browser $tags) use ($post): void {
                            foreach ($post->tags as $tag) {
                                $tags->assertSee(Str::upper($tag->getName()));
                            }
                        });

                    $row->click('@line')
                        ->assertRouteIs('posts-by-lines', ['slug' => $post->line->getSlug()]);
                });
        });
    }

    public function testDontShowPhotoCreditIfThereIsntOne(): void
    {
        Post::factory()->bySuperAdmin()->withoutPhotoCredit()->now()->create();

        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $row): void {
                    $row->assertMissing('@photo-credit');
                });
        });
    }
}
