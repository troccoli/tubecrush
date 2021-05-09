<?php

namespace Tests\Browser;

use Tests\DuskTestCase;

class VoteTest extends DuskTestCase
{
    public function testUserCannotVoteIfConsentWasRefused()
    {
        $this->browse(function (Browser $browser) {
            $browser->refuseCookies()
                ->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $post): void {
                    $post->assertDisabled('@likes-button');
                });
        });
    }

    public function testUserCanVoteIfConsentWasGiven()
    {
        $this->browse(function (Browser $browser) {
            $browser->acceptCookies()
                ->visitRoute('home')
                ->with('[dusk="post"]:first-child', function (Browser $post): void {
                    $post->assertEnabled('@likes-button')
                        ->assertVisible('@likes-icon-not-voted')
                        ->assertMissing('@likes-icon-voted')
                        ->click('@likes-button')
                        ->waitFor('@likes-icon-voted')
                        ->assertMissing('@likes-icon-not-voted')
                        ->click('@likes-button')
                        ->waitFor('@likes-icon-not-voted')
                        ->assertMissing('@likes-icon-voted');
                });
        });
    }
}
