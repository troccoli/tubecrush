<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Line;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class EditPostTest extends TestCase
{
    protected Post $post;

    public function testTheComponentIsRendered(): void
    {
        $this->get(route('posts.update', ['postId' => $this->post->getId()]))
            ->assertSeeLivewire('posts.edit-post');
    }

    public function testTheTileIsRequiredAndCannotBeLongerThan50Characters(): void
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

    public function testItUpdatesAPost(): void
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

        $this->post = Post::factory()->for($this->superAdmin(), 'author')->create(['title' => 'Old post']);
        $this->be($this->superAdmin());
    }
}
