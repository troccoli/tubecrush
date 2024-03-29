<?php

namespace Tests\Browser;

use App\Models\AlternativePostSlug;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\DuskTestCase;

class PostTest extends DuskTestCase
{
    public function testCreatingAPost(): void
    {
        // Create a post with the slug 'must-be-unique'
        $post = Post::factory()->create(['title' => 'Must be unique']);
        // Create a alternative slug for the same post
        AlternativePostSlug::factory()->for($post)->create(['slug' => 'old-existing-slug']);
        $this->browse(function (Browser $browser): void {
            $postCount = Post::query()->count();
            $tags = Tag::query()->inRandomOrder()->limit(3)->get();
            $browser->loginAs($this->superAdmin);

            // The form has the correct fields and buttons
            $browser->visitRoute('posts.create')
                ->assertSee('Title')
                ->assertInputValue('#title', '')
                ->assertSee('Line')
                ->assertSeeIn('@line-select', 'Choose one line')
                ->assertSee('Content')
                ->assertInputValue('#content', '')
                ->assertSeeIn('@upload-photo-button', 'Upload a photo')
                ->assertMissing('@photo-image')
                ->assertSee('Photo submitted by')
                ->assertInputValue('#photo-credit', '')
                ->assertSee('Tags')
                ->within('@tags-select', function (Browser $select): void {
                    $select->waitFor('.select2-search__field')
                        ->assertAttribute(
                            '.select2-search__field',
                            'placeholder',
                            'Start typing to search for tags'
                        );
                })
                ->assertSeeIn('@cancel-button', 'CANCEL')
                ->assertSeeIn('@submit-button', 'CREATE');

            // Can cancel the operation
            $browser->visitRoute('posts.create')
                ->press('CANCEL')
                ->waitForReload()
                ->assertRouteIs('posts.list');
            $this->assertDatabaseCount('posts', $postCount);

            // The title is mandatory, and cannot be more than 50 characters
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitForTextIn('@title-error', 'The title field is required.')
                ->type('#title', Str::random(51))
                ->press('CREATE')
                ->waitForTextIn('@title-error', 'The title field must not be greater than 50 characters.')
                ->type('#title', Str::random(50))
                ->press('CREATE')
                ->waitUntilMissing('@title-error');

            // The generated slug must be unique
            $browser->visitRoute('posts.create')
                ->type('#title', 'Must be unique')
                ->press('CREATE')
                ->waitForTextIn('@title-error', 'A slug for this title has already been used in the past.')
                ->type('#title', 'Old Existing SLUG')
                ->press('CREATE')
                ->waitForTextIn('@title-error', 'A slug for this title has already been used in the past.');

            // The line is mandatory
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitForTextIn('@line-error', 'The selected line is invalid.')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->waitForTextIn('@line-select', 'Circle line')
                ->press('CREATE')
                ->waitUntilMissing('@line-error');

            // The content is mandatory, must be at least 10 characters and cannot be more than 2000 characters
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitForTextIn('@content-error', 'The content field is required.')
                ->type('#content', Str::random(9))
                ->press('CREATE')
                ->waitForTextIn('@content-error', 'The content field must be at least 10 characters.')
                ->type('#content', Str::random(2001))
                ->press('CREATE')
                ->waitForTextIn('@content-error', 'The content field must not be greater than 2000 characters.')
                ->type('#content', Str::random(10))
                ->press('CREATE')
                ->waitUntilMissing('@content-error')
                ->type('#content', Str::random(2000))
                ->press('CREATE')
                ->waitUntilMissing('@content-error');

            // The photo is mandatory, must be jpg, jpeg or png
            $textFile = UploadedFile::fake()->create('file.txt', 'Text files are not allowed');
            $jpgFile = UploadedFile::fake()->image('photo1.jpg');
            $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
            $pngFile = UploadedFile::fake()->image('photo3.png');
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitForTextIn('@photo-error', 'The photo field is required.')
                ->assertMissing('@photo-image')
                ->attach('#photo', $textFile)
                ->waitForTextIn('@photo-error', 'The photo field must be a file of type: jpg, jpeg, png.')
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
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertMissing('@photo-credit-error')
                ->type('#photo-credit', Str::random(21))
                ->press('CREATE')
                ->waitForTextIn('@photo-credit-error', 'The photo credit field must not be greater than 20 characters.')
                ->type('#photo-credit', Str::random(20))
                ->press('CREATE')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertMissing('@photo-credit-error');

            // The tags are optional
            $browser->visitRoute('posts.create')
                ->press('CREATE')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertMissing('@tags-error');

            // Create a new post with credit and tags
            $browser->visitRoute('posts.create')
                ->type('#title', 'New post title')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->waitForTextIn('@line-select', 'Circle line')
                ->type('#content', 'New post amazing content')
                ->attach('#photo', $pngFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->type('#photo-credit', 'John')
                ->within('@tags-select', function (Browser $select) use ($tags): void {
                    foreach ($tags as $tag) {
                        $select->keys('.select2-search__field', $tag->getName(), '{return_key}');
                    }
                    $select->keys('.select2-search__field', '{escape}');
                })
                ->press('CREATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->assertSee('New post title');

            $this->assertDatabaseCount('posts', $postCount + 1);
            $this->assertDatabaseHas('posts', ['title' => 'New post title']);
            /** @var Collection $postTags */
            $postTags = Post::query()->whereTitle('New post title')->first()->tags->pluck('id');
            $this->assertSameSize($tags, $postTags);
            foreach ($tags as $tag) {
                /** @var Tag $tag */
                $this->assertTrue($postTags->contains($tag->getKey()));
            }

            // Create a new post without credit or tags
            $browser->visitRoute('posts.create')
                ->type('#title', 'Another new post')
                ->click('@line-select')
                ->waitFor('@district-line-option')
                ->click('@district-line-option')
                ->waitForTextIn('@line-select', 'District line')
                ->type('#content', 'More wonderful content')
                ->attach('#photo', $jpgFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->press('CREATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->assertSee('New post title');

            $this->assertDatabaseCount('posts', $postCount + 2);
            $this->assertDatabaseHas('posts', ['title' => 'Another new post']);

            $browser->logout();
        });
    }

    public function testUpdatingAPost(): void
    {
        // Create a post with the slug 'must-be-unique'
        $post = Post::factory()->create(['title' => 'Must be unique']);
        // Create a alternative slug for the same post
        AlternativePostSlug::factory()->for($post)->create(['slug' => 'old-existing-slug']);
        Post::factory()->bySuperAdmin()->withTitle('Short title')->hasTags(3)->now()->create();
        $this->browse(function (Browser $browser): void {
            /** @var Post $latestPost */
            $latestPost = Post::query()->latest()->first();
            $postCount = Post::query()->count();
            $newTags = Tag::query()->inRandomOrder()->limit(2)->get();
            $browser->loginAs($this->superAdmin);

            // The form has the correct fields, values and buttons
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->assertSee('Title')
                ->assertInputValue('#title', $latestPost->getTitle())
                ->assertSee('Line')
                ->assertSeeIn('@line-select', $latestPost->getLine()->getName())
                ->assertSee('Content')
                ->assertInputValue('#content', $latestPost->getContent())
                ->assertSeeIn('@upload-photo-button', 'Upload a photo')
                ->assertAttribute('@photo-image', 'src', '/storage/'.$latestPost->getPhoto())
                ->assertSee('Photo submitted by')
                ->assertInputValue('#photo-credit', $latestPost->getPhotoCredit())
                ->assertSee('Tags')
                ->with('@tags-select', function (Browser $select) use ($latestPost): void {
                    $select->waitFor('.selection');
                    foreach ($latestPost->tags as $tag) {
                        $select->assertSeeIn('.selection', Str::upper($tag->getName()));
                    }
                })
                ->assertSeeIn('@cancel-button', 'CANCEL')
                ->assertSeeIn('@submit-button', 'UPDATE');

            // Can cancel the operation
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->press('CANCEL')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->assertSee($latestPost->getTitle());

            $this->assertDatabaseHas('posts', ['title' => $latestPost->getTitle()]);

            // The title is mandatory, and cannot be more than 50 characters
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->keys('#title', ['{control}', 'a'], '{backspace}') // clear() or type('#title','') don't seem to work
                ->press('UPDATE')
                ->waitForTextIn('@title-error', 'The title field is required.')
                ->type('#title', Str::random(51))
                ->press('UPDATE')
                ->waitForTextIn('@title-error', 'The title field must not be greater than 50 characters.');

            // The generated slug must be unique
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->type('#title', 'Must be unique')
                ->press('UPDATE')
                ->waitForTextIn('@title-error', 'A slug for this title has already been used in the past.')
                ->type('#title', 'Old Existing SLUG')
                ->press('UPDATE')
                ->waitForTextIn('@title-error', 'A slug for this title has already been used in the past.');

            // The content is mandatory, must be at least 10 characters and cannot be more than 2000 characters
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->keys('#content', ['{control}', 'a'], '{backspace}') // clear() or type('#title','') don't seem to work
                ->press('UPDATE')
                ->waitForTextIn('@content-error', 'The content field is required.')
                ->type('#content', Str::random(9))
                ->press('UPDATE')
                ->waitForTextIn('@content-error', 'The content field must be at least 10 characters.')
                ->type('#content', Str::random(2001))
                ->press('UPDATE')
                ->waitForTextIn('@content-error', 'The content field must not be greater than 2000 characters.');

            // The photo must be jpg, jpeg or png
            $textFile = UploadedFile::fake()->create('file.txt', 'Text files are not allowed');
            $jpgFile = UploadedFile::fake()->image('photo1.jpg');
            $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
            $pngFile = UploadedFile::fake()->image('photo3.png');
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->attach('#photo', $textFile)
                ->waitForTextIn('@photo-error', 'The photo field must be a file of type: jpg, jpeg, png.')
                ->assertAttribute('@photo-image', 'src', '/storage/'.$latestPost->getPhoto())
                ->attach('#photo', $jpgFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error')
                ->attach('#photo', $jpegFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error')
                ->attach('#photo', $pngFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->assertMissing('@photo-error');

            // The photo credit is optional but if present must be no more than 20 characters
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->type('#photo-credit', '')
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->type('#photo-credit', Str::random(21))
                ->press('UPDATE')
                ->waitForTextIn('@photo-credit-error', 'The photo credit field must not be greater than 20 characters.')
                ->type('#photo-credit', Str::random(20))
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list');

            // The tags are optional
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->within('@tags-select', function (Browser $select) use ($latestPost): void {
                    foreach ($latestPost->tags as $tag) {
                        $select->keys('.select2-search__field', '{backspace}');
                        for ($i = 1; $i <= Str::length($tag->getName()); $i++) {
                            $select->keys('.select2-search__field', '{backspace}');
                        }
                    }
                    $select->keys('.select2-search__field', '{escape}');
                })
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list');

            // Update a post with photo credit and tags
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->keys(
                    '#title',
                    ['{control}', 'a'],
                    '{backspace}',
                    'New title for post'
                ) // clear() or type('#title','') don't seem to work
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->keys(
                    '#content',
                    ['{control}', 'a'],
                    '{backspace}',
                    'New content for post'
                ) // clear() or type('#content','') don't seem to work
                ->attach('#photo', $jpgFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->keys(
                    '#photo-credit',
                    ['{control}', 'a'],
                    '{backspace}',
                    'John'
                ) // clear() or type('#photo-credit','') don't seem to work
                ->within('@tags-select', function (Browser $select) use ($latestPost, $newTags): void {
                    foreach ($latestPost->tags as $tag) {
                        $select->keys('.select2-search__field', '{backspace}');
                        for ($i = 1; $i <= Str::length($tag->getName()); $i++) {
                            $select->keys('.select2-search__field', '{backspace}');
                        }
                    }
                    $select->keys('.select2-search__field', '{escape}');
                    foreach ($newTags as $tag) {
                        $select->keys('.select2-search__field', $tag->getName(), '{return_key}');
                    }
                })
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->assertSee('New title for post');

            $this->assertDatabaseCount('posts', $postCount);
            $latestPost->refresh();
            $this->assertSameSize($newTags, $latestPost->tags->pluck('id'));
            foreach ($newTags as $tag) {
                /** @var Tag $tag */
                $this->assertTrue($latestPost->tags->contains($tag->getKey()));
            }

            // Update a post without photo credit or tags
            $browser->visitRoute('posts.update', ['postId' => $latestPost->getKey()])
                ->type('#title', 'New title for post')
                ->click('@line-select')
                ->waitFor('@circle-line-option')
                ->click('@circle-line-option')
                ->keys(
                    '#content',
                    ['{control}', 'a'],
                    '{backspace}',
                    'New content for post'
                ) // clear() or type('#content','') don't seem to work
                ->attach('#photo', $jpgFile)
                ->waitUntilMissing('@photo-loading-icon')
                ->keys(
                    '#photo-credit',
                    ['{control}', 'a'],
                    '{backspace}'
                ) // clear() or type('#photo-credit','') don't seem to work
                ->within('@tags-select', function (Browser $select) use ($latestPost): void {
                    foreach ($latestPost->tags as $tag) {
                        $select->keys('.select2-search__field', '{backspace}');
                        for ($i = 1; $i <= Str::length($tag->getName()); $i++) {
                            $select->keys('.select2-search__field', '{backspace}');
                        }
                    }
                    $select->keys('.select2-search__field', '{escape}');
                })
                ->press('UPDATE')
                ->waitForReload()
                ->assertRouteIs('posts.list')
                ->assertSee('New title for post');

            $this->assertDatabaseCount('posts', $postCount);
            $latestPost->refresh();
            $this->assertEmpty($latestPost->tags);

            $browser->logout();
        });
    }

    public function testViewingSinglePost(): void
    {
        $post = Post::factory()->bySuperAdmin()->withTitle('Short title')->hasTags(3)->now()->create();
        $this->browse(function (Browser $browser) use ($post): void {
            $browser->visitRoute('single-post', compact('post'))
                ->with('[dusk="post"]', function (Browser $row) use ($post): void {
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

                    $row->click('@line')
                        ->assertRouteIs('posts-by-lines', ['slug' => $post->line->getSlug()]);
                });
        });
    }

    /*
     * This test should be part of either testCreatingAPost or testUpdatingAPost. However, what we test here fails
     * for some reason (it works in the app), and we don't want that to stop the test suites.
     * By having them in their own test and mark it as skipped we make sure we don't forget about them and,
     * hopefully, we will fix them eventually.
     */
    public function testContentCannotBeTooLong(): void
    {
        $this->markTestSkipped();
        $this->browse(function (Browser $browser): void {
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.create')
                ->type('#content', Str::random(2001))
                ->press('CREATE')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertSeeIn('@content-error', 'The content field must not be greater than 2000 characters.')
                ->logout();

            $latestPost = Post::query()->latest()->first();
            $browser->loginAs($this->superAdmin)
                ->visitRoute('posts.update', ['post' => $latestPost])
                ->type('#content', Str::random(2001))
                ->press('UPDATE')
                ->waitUntilMissing('@submit-loading-icon')
                ->assertSeeIn('@content-error', 'The content field must not be greater than 2000 characters.')
                ->logout();
        });
    }

    public function testCommentsAreAvailableOnTheSinglePostPage(): void
    {
        $post = Post::query()->latest()->first();

        $this->browse(function (Browser $browser) use ($post) {
            $browser->visitRoute('single-post', ['post' => $post])
                ->assertVisible('@comments');
        });
    }
}
