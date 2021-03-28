<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Line;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class EditPostTest extends TestCase
{
    protected Post $post;
    protected Collection $tags;

    public function testTheComponentIsRendered(): void
    {
        $this->get(route('posts.update', ['postId' => $this->post->getId()]))
            ->assertSeeLivewire('posts.edit-post');
    }

    public function testTheTitleIsRequiredAndCannotBeLongerThan50Characters(): void
    {
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('title', '')
            ->call('submit')
            ->assertHasErrors(['title' => 'required'])
            ->set('title', Str::random(51))
            ->call('submit')
            ->assertHasErrors(['title' => 'max']);
    }

    public function testTheLineIsRequiredAndMustBeAnExistingLine(): void
    {
        Livewire::test('posts.create-post')
            ->set('line', 0)
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
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('content', '')
            ->call('submit')
            ->assertHasErrors(['content' => 'required'])
            ->set('content', Str::random(2001))
            ->call('submit')
            ->assertHasErrors(['content' => 'max'])
            ->set('content', Str::random(9))
            ->call('submit')
            ->assertHasErrors(['content' => 'min'])
            ->set('content', Str::random(10))
            ->call('submit')
            ->assertHasNoErrors(['content' => 'min']);
    }

    public function testThePhotoMustBeAnImage(): void
    {
        Storage::fake('public');

        $textFile = UploadedFile::fake()->create('file.txt', 1000, 'text/plain');
        $jpgFile = UploadedFile::fake()->image('photo.jpg');
        $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
        $pngFile = UploadedFile::fake()->image('photo3.png');

        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('photo', $textFile)
            ->call('submit')
            ->assertHasErrors(['photo' => 'mimes']);
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('photo', $jpgFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes']);
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('photo', $jpegFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes']);
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('photo', $pngFile)
            ->call('submit')
            ->assertHasNoErrors(['photo' => 'mimes']);
    }

    public function testThePhotoCreditIsOptionalButMustBeFewerThan20Characters(): void
    {
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('photoCredit', '')
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
        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('tags', [])
            ->call('submit')
            ->assertHasNoErrors(['tags', 'tags.*']);
    }

    public function testItUpdatesAPost(): void
    {
        $this->assertDatabaseMissing('posts', ['title' => 'New post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old post']);

        $newTags = Tag::query()->inRandomOrder()->limit(5)->get();

        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('title', "New post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photoCredit', 'John')
            ->set('tags', $newTags->pluck('id')->toArray())
            ->call('submit');

        $this->assertDatabaseHas('posts', ['title' => 'New post']);
        $this->assertDatabaseMissing('posts', ['title' => 'Old post']);

        /** @var Collection $postTags */
        $postTags = $this->superAdmin()->posts->first()->tags;
        $this->assertSameSize($newTags, $postTags);
        foreach ($newTags as $tag) {
            $this->assertTrue($postTags->contains($tag));
        }
    }

    public function testItUpdatesAPostWithoutThePhotoCredit(): void
    {
        $this->assertDatabaseMissing('posts', ['title' => 'New post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old post']);

        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('title', "New post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photoCredit', null)
            ->call('submit');

        $this->assertDatabaseHas('posts', ['title' => 'New post']);
        $this->assertDatabaseMissing('posts', ['title' => 'Old post']);
    }

    public function testItUpdatesAPostWithoutTags(): void
    {
        $this->assertDatabaseMissing('posts', ['title' => 'New post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old post']);

        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('title', "New post")
            ->set('line', 1)
            ->set('content', 'Amazing content for this new post')
            ->set('photoCredit', 'John')
            ->call('submit');

        $this->assertDatabaseHas('posts', ['title' => 'New post']);
        $this->assertDatabaseMissing('posts', ['title' => 'Old post']);
    }

    public function testItCanCancel(): void
    {
        $this->assertDatabaseMissing('posts', ['title' => 'New post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old post']);

        Livewire::test('posts.edit-post', ['postId' => $this->post->getId()])
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('cancelEdit');

        $this->assertDatabaseMissing('posts', ['title' => 'New post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old post']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->post = Post::factory()->bySuperAdmin()->withTitle('Old post')->create();
        $this->tags = Tag::query()->inRandomOrder()->limit(3)->get();

        $this->post->tags()->sync($this->tags);

        $this->be($this->superAdmin());
    }
}
