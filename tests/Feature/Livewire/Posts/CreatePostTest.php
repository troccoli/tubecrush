<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Line;
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
            ->assertHasErrors(['title' => 'max']);
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
            ->set('content', Str::random(9))
            ->call('submit')
            ->assertHasErrors(['content' => 'min'])
            ->set('content', Str::random(10))
            ->call('submit')
            ->assertHasNoErrors(['content' => 'min']);
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

    public function testItCreatesANewPost(): void
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
