<?php

namespace Tests\Browser;

use App\Models\Post;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ShareTest extends DuskTestCase
{
    public function testShare(): void
    {
        Post::factory()->withTitle('To be shared')->now()->publishedNow()->create();
        $this->browse(function (Browser $browser) {
            $textParam = urlencode("To be shared {$this->baseUrl()}/post/to-be-shared");
            $uParam = urlencode("{$this->baseUrl()}/post/to-be-shared");

            $browser->acceptCookies()
                ->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $post) use ($textParam, $uParam): void {
                    $post
                        ->assertVisible('@twitter-share')
                        ->within('@twitter-share', function (Browser $share) use ($textParam): void {
                            $share->assertAttribute('a', 'title', 'Share on Twitter')
                                ->assertAttribute(
                                    'a',
                                    'href',
                                    "https://twitter.com/intent/tweet?via=tubecrush&text=$textParam"
                                );
                        })
                        ->assertVisible('@facebook-share')
                        ->within('@facebook-share', function (Browser $share) use ($uParam): void {
                            $share->assertAttribute('a', 'title', 'Share on Facebook')
                                ->assertAttribute(
                                    'a',
                                    'href',
                                    "https://www.facebook.com/sharer/sharer.php?u=$uParam"
                                );
                        })
                        ->assertVisible('@copy-link-share')
                        ->within('@copy-link-share', function (Browser $share): void {
                            $share->assertAttribute('a', 'title', 'Copy URL')
                                ->click('@copy-link-share-link');
                        });
                });

            $browser->visitRoute('single-post', ['post' => 'to-be-shared'])
                ->with('[dusk="post"]', function (Browser $post) use ($textParam, $uParam): void {
                    $post->assertVisible('@twitter-share')
                        ->within('@twitter-share', function (Browser $share) use ($textParam): void {
                            $share->assertAttribute('a', 'title', 'Share on Twitter')
                                ->assertAttribute(
                                    'a',
                                    'href',
                                    "https://twitter.com/intent/tweet?via=tubecrush&text=$textParam"
                                );
                        })
                        ->assertVisible('@facebook-share')
                        ->within('@facebook-share', function (Browser $share) use ($uParam): void {
                            $share->assertAttribute('a', 'title', 'Share on Facebook')
                                ->assertAttribute(
                                    'a',
                                    'href',
                                    "https://www.facebook.com/sharer/sharer.php?u=$uParam"
                                );
                        })
                        ->assertVisible('@copy-link-share')
                        ->within('@copy-link-share', function (Browser $share): void {
                            $share->assertAttribute('a', 'title', 'Copy URL');
                        });
                });
        });
    }
}
