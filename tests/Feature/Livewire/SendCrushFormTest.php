<?php

namespace Tests\Feature\Livewire;

use App\Enums\PostStatus;
use App\Enums\UserRoles;
use App\Events\NewTubeCrushSubmitted;
use App\Http\Livewire\SendCrushForm;
use App\Models\Line;
use App\Models\Post;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class SendCrushFormTest extends TestCase
{
    /** @test */
    public function testTheComponentCanRender(): void
    {
        Livewire::test(SendCrushForm::class)->assertStatus(200);
    }

    public function testThePhotoIsRequiredAndMustBeAnImageAndMustNotBeBiggerThan5MB(): void
    {
        Event::fake();

        Storage::fake('public');

        $textFile = UploadedFile::fake()->create('file.txt', 1000, 'text/plain');
        $jpgFile = UploadedFile::fake()->image('photo.jpg');
        $jpegFile = UploadedFile::fake()->image('photo2.jpeg');
        $pngFile = UploadedFile::fake()->image('photo3.png');

        Livewire::test(SendCrushForm::class)
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

        Event::assertNotDispatched(NewTubeCrushSubmitted::class);
    }

    public function testThePhotoCreditIsOptionalButMustBeFewerThan20Characters(): void
    {
        Event::fake();

        Livewire::test(SendCrushForm::class)
            ->call('submit')
            ->assertHasNoErrors(['photoCredit'])
            ->set('photoCredit', Str::random(21))
            ->call('submit')
            ->assertHasErrors(['photoCredit' => 'max'])
            ->set('photoCredit', Str::random(20))
            ->call('submit')
            ->assertHasNoErrors(['photoCredit']);

        Event::assertNotDispatched(NewTubeCrushSubmitted::class);
    }

    public function testTheLineIsRequiredAndMustBeAnExistingLine(): void
    {
        Event::fake();

        Livewire::test(SendCrushForm::class)
            ->call('submit')
            ->assertHasErrors(['line' => 'exists'])
            ->set('line', Line::query()->max('id') + 1)
            ->call('submit')
            ->assertHasErrors(['line' => 'exists'])
            ->set('line', Line::query()->max('id'))
            ->call('submit')
            ->assertHasNoErrors(['line' => 'exists']);

        Event::assertNotDispatched(NewTubeCrushSubmitted::class);
    }

    public function testItCanSendACrush(): void
    {
        Event::fake();

        Storage::fake('public');

        $photo = UploadedFile::fake()->image('photo.jpg');
        $postCount = Post::count();

        $this->assertDatabaseMissing(Post::class, [
            'title' => 'New TubeCrush submitted',
        ]);

        Livewire::test(SendCrushForm::class)
            ->set('photo', $photo)
            ->set('line', 1)
            ->set('photoCredit', 'Giulio')
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas(Post::class, [
            'title' => 'New TubeCrush submitted',
        ]);
        $this->assertCount($postCount + 1, Post::all());

        /** @var Post $post */
        $post = Post::query()->whereTitle('New TubeCrush submitted')->first();
        $this->assertSame(1, $post->getLine()->getKey());
        $this->assertSame('Giulio', $post->getPhotoCredit());
        $this->assertSame(PostStatus::Draft, $post->getStatus());
        $this->assertNull($post->getPublishedDate());
        $this->assertTrue($post->author->hasRole(UserRoles::Editor->value));

        Event::assertDispatchedTimes(NewTubeCrushSubmitted::class, 1);
        Event::assertDispatched(function (NewTubeCrushSubmitted $event) use ($post) {
            return $event->post->getKey() === $post->getKey();
        });
    }

    public function testItCanSendACrushWithoutPhotoCredit(): void
    {
        Event::fake();

        Storage::fake('public');

        $photo = UploadedFile::fake()->image('photo.jpg');
        $postCount = Post::count();

        $this->assertDatabaseMissing(Post::class, [
            'title' => 'New TubeCrush submitted',
        ]);

        Livewire::test(SendCrushForm::class)
            ->set('photo', $photo)
            ->set('line', 1)
            ->call('submit')
            ->assertHasNoErrors();

        $this->assertDatabaseHas(Post::class, [
            'title' => 'New TubeCrush submitted',
        ]);
        $this->assertCount($postCount + 1, Post::all());

        /** @var Post $post */
        $post = Post::query()->whereTitle('New TubeCrush submitted')->first();
        $this->assertSame(1, $post->getLine()->getKey());
        $this->assertEmpty($post->getPhotoCredit());
        $this->assertSame(PostStatus::Draft, $post->getStatus());
        $this->assertNull($post->getPublishedDate());
        $this->assertTrue($post->author->hasRole(UserRoles::Editor->value));

        Event::assertDispatchedTimes(NewTubeCrushSubmitted::class, 1);
        Event::assertDispatched(function (NewTubeCrushSubmitted $event) use ($post) {
            return $event->post->getKey() === $post->getKey();
        });
    }
}
