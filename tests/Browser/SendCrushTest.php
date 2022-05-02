<?php

namespace Tests\Browser;

use App\Enums\PostStatus;
use App\Models\Line;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class SendCrushTest extends DuskTestCase
{
    public function testClickingTheButtonDisplaysTheForm(): void
    {
        $this->browse(function (Browser $browser): void {
            $browser->visitRoute('home')
                ->click('@send-crush-button')
                ->waitForRoute('send-crush')
                ->assertVisible('@send-crush-form');
        });
    }

    public function testSendCrushFrom(): void
    {
        $this->browse(function (Browser $browser): void {
            $postCount = Post::query()->count();

            $browser->visitRoute('send-crush')
                ->assertSee('Line')
                ->assertSeeIn('@line-select', 'Choose one line')
                ->assertSeeIn('@upload-photo-button', 'Upload a photo')
                ->assertMissing('@photo-image')
                ->assertSee('Photo submitted by')
                ->assertInputValue('#photo-credit', '')
                ->assertSeeIn('@clear-button', 'CLEAR')
                ->assertSeeIn('@submit-button', 'SEND');

            // The line is mandatory
            $browser->visitRoute('send-crush')
                ->press('SEND')
                ->waitForTextIn('@line-error', 'The selected line is invalid.')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->waitForTextIn('@line-select', 'Circle line')
                ->press('SEND')
                ->waitUntilMissing('@line-error');

            // The photo is mandatory, must be jpg, jpeg or png
            $textFile = UploadedFile::fake()->create('file.txt', 1000, 'text/plain');
            $jpgFile = UploadedFile::fake()->image('photo1.jpg');
            $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
            $pngFile = UploadedFile::fake()->image('photo3.png');
            $browser->visitRoute('send-crush')
                ->press('SEND')
                ->waitForTextIn('@photo-error', 'The photo field is required.')
                ->assertMissing('@photo-image')
                ->attach('#photo', $textFile)
                ->waitForTextIn('@photo-error', 'The photo must be a file of type: jpg, jpeg, png.')
                ->assertMissing('@photo-image')
                ->attach('#photo', $jpgFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error')
                ->assertPresent('@photo-image')
                ->attach('#photo', $jpegFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error')
                ->assertPresent('@photo-image')
                ->attach('#photo', $pngFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error')
                ->assertPresent('@photo-image');

            // The photo credit is optional but if present must be no more than 20 characters
            $browser->visitRoute('send-crush')
                ->press('SEND')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertMissing('@photo-credit-error')
                ->type('#photo-credit', Str::random(21))
                ->press('SEND')
                ->waitForTextIn('@photo-credit-error', 'The photo credit may not be greater than 20 characters.')
                ->type('#photo-credit', Str::random(20))
                ->press('SEND')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertMissing('@photo-credit-error');

            // Can clear the form
            $browser->visitRoute('send-crush')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->waitForTextIn('@line-select', 'Circle line')
                ->attach('#photo', $pngFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->type('#photo-credit', 'John')
                ->pressAndWaitFor('CLEAR')
                ->assertRouteIs('send-crush')
                ->waitForTextIn('@line-select', 'Choose one line')
                ->assertSeeIn('@upload-photo-button', 'Upload a photo')
                ->assertMissing('@photo-image')
                ->assertInputValue('#photo-credit', '');

            $this->assertDatabaseMissing('posts', [
                'title' => 'New TubeCrush submitted',
            ]);

            $browser->visitRoute('send-crush')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->waitForTextIn('@line-select', 'Circle line')
                ->attach('#photo', $pngFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->press('SEND')
                ->waitForRoute('send-crush-success');

            $this->assertDatabaseCount('posts', $postCount + 1);
            $this->assertDatabaseHas('posts', [
                'title' => 'New TubeCrush submitted',
                'line_id' => Line::whereName('Circle line')->first()->getKey(),
                'status' => PostStatus::Draft,
                'published_at' => null,
            ]);

            $browser->logout();
        });
    }
}
