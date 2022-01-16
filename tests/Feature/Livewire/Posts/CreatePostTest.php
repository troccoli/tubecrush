<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\AlternativePostSlug;
use App\Models\Line;
use App\Models\Post;
use App\Models\Tag;
use App\Rules\UniquePostSlug;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class CreatePostTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('posts.create'))
            ->assertSeeLivewire('posts.create-post');
    }

    public function testTheTitleIsRequiredAndCannotBeLongerThan50Characters(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasErrors(['title' => 'required'])
            ->set('title', Str::random(51))
            ->call('submit')
            ->assertHasErrors(['title' => 'max'])
            ->set('title', Str::random(50))
            ->call('submit')
            ->assertHasNoErrors(['title']);
    }

    public function testTheSlugMustBeUnique()
    {
        Post::factory()->create(['title' => 'First post']);
        AlternativePostSlug::factory()->for(Post::factory())->create(['slug' => 'existing-slug']);
        Livewire::test('posts.create-post')
            ->set('title', 'First post')
            ->call('submit')
            ->assertHasErrors(['title' => UniquePostSlug::class])
            ->set('title', 'Existing Slug')
            ->call('submit')
            ->assertHasErrors(['title' => UniquePostSlug::class]);
    }

    public function testTheLineIsRequiredAndMustBeAnExistingLine(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasErrors(['line' => 'exists'])
            ->set('line', Line::query()->max('id') + 1)
            ->call('submit')
            ->assertHasErrors(['line' => 'exists'])
            ->set('line', Line::query()->max('id'))
            ->call('submit')
            ->assertHasNoErrors(['line' => 'exists']);
    }

    public function testTheContentIsRequiredAndMustBeAtLeast10CharactersLongAndNoMoreThan2000(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasErrors(['content' => 'required'])
            ->set('content', Str::random(2001))
            ->call('submit')
            ->assertHasErrors(['content' => 'max'])
            ->set('content', Str::random(2000))
            ->call('submit')
            ->assertHasNoErrors(['content'])
            ->set('content', Str::random(9))
            ->call('submit')
            ->assertHasErrors(['content' => 'min'])
            ->set('content', Str::random(10))
            ->call('submit')
            ->assertHasNoErrors(['content']);
    }

    public function testThePhotoIsRequiredAndMustBeAnImageAndMustNotBeBiggerThan5MB(): void
    {
        Storage::fake('public');

        $textFile = UploadedFile::fake()->create('file.txt', 1000, 'text/plain');
        $jpgFile = UploadedFile::fake()->image('photo.jpg');
        $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
        $pngFile = UploadedFile::fake()->image('photo3.png');

        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasErrors(['photo' => 'required'])
            ->set('photo', $textFile)
            ->call('submit')
            ->assertHasErrors(['photo' => 'mimes'])
            ->set('photo', $jpgFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes'])
            ->set('photo', $jpegFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes'])
            ->set('photo', $pngFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes']);
    }

    public function testThePhotoCreditIsOptionalButMustBeFewerThan20Characters(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasNoErrors(['photoCredit'])
            ->set('photoCredit', Str::random(21))
            ->call('submit')
            ->assertHasErrors(['photoCredit' => 'max'])
            ->set('photoCredit', Str::random(20))
            ->call('submit')
            ->assertHasNoErrors(['photoCredit']);
    }

    public function testTheTagsAreOptional(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasNoErrors(['tags', 'tags.*']);
    }

    public function testItCreatesANewPost(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superAdmin());
        $photo = UploadedFile::fake()->image('photo.jpg');
        $tags = Tag::query()->inRandomOrder()->limit(3)->get();

        Livewire::test('posts.create-post')
            ->set('title', "New Post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photo', $photo)
            ->set('photoCredit', 'John')
            ->set('tags', $tags->pluck('id')->toArray())
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertCount(1, $this->superAdmin()->posts);

        /** @var Post $post */
        $post = $this->superAdmin()->posts->first();
        $this->assertSame('new-post', $post->getSlug());

        /** @var Collection $postTags */
        $postTags = $post->tags;
        $this->assertSameSize($tags, $postTags);
        foreach ($tags as $tag) {
            $this->assertTrue($postTags->contains($tag));
        }
        Storage::disk('public')->assertExists($this->superAdmin()->posts->first()->getPhoto());
    }

    public function testItCanCreateANewPostWithoutPhotoCredit(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superAdmin());
        $photo = UploadedFile::fake()->image('photo.jpg');

        Livewire::test('posts.create-post')
            ->set('title', "New Post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photo', $photo)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertCount(1, $this->superAdmin()->posts);
        Storage::disk('public')->assertExists($this->superAdmin()->posts->first()->getPhoto());
    }

    public function testItCanCreateANewPostWithoutTags(): void
    {
        Storage::fake('public');

        $this->actingAs($this->superAdmin());
        $photo = UploadedFile::fake()->image('photo.jpg');

        Livewire::test('posts.create-post')
            ->set('title', "New Post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photo', $photo)
            ->set('photoCredit', 'John')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertCount(1, $this->superAdmin()->posts);
        Storage::disk('public')->assertExists($this->superAdmin()->posts->first()->getPhoto());
    }

    public function testItCanCancel(): void
    {
        $this->actingAs($this->superAdmin());

        Livewire::test('posts.create-post')
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('cancelCreate');

        $this->assertDatabaseMissing('posts', ['title' => 'New Post']);
        $this->assertCount(0, $this->superAdmin()->posts);
    }
}
