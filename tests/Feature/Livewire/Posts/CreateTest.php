<?php

namespace Tests\Feature\Livewire\Posts;

use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class CreateTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('posts.create'))
            ->assertSeeLivewire('posts.form');
    }

    public function testTheTileIsRequiredAndCannotBeLongerThan50Characters(): void
    {
//        Event::fake();
//        Notification::fake();

        Livewire::test('posts.form')
            ->call('createPost')
            ->assertHasErrors(['title' => 'required'])
            ->set('title', Str::random(51))
            ->call('createPost')
            ->assertHasErrors(['title' => 'max']);

//        Event::assertNotDispatched(SomeoneHasContactedUs::class);
//        Notification::assertNothingSent();
    }

    public function testTheContentIsRequiredAndMustBeAtLeast10CharactersLongAndNoMoreThan2000(): void
    {
//        Event::fake();
//        Notification::fake();

        Livewire::test('posts.form')
            ->call('createPost')
            ->assertHasErrors(['content' => 'required'])
            ->set('content', Str::random(2001))
            ->call('createPost')
            ->assertHasErrors(['content' => 'max'])
            ->set('content', Str::random(9))
            ->call('createPost')
            ->assertHasErrors(['content' => 'min'])
            ->set('content', Str::random(10))
            ->call('createPost')
            ->assertHasNoErrors(['content' => 'min']);

//        Event::assertNotDispatched(SomeoneHasContactedUs::class);
//        Notification::assertNothingSent();
    }

    public function testItCreatesANewPost(): void
    {
        $this->actingAs($this->superAdmin());

        Livewire::test('posts.form')
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('createPost');

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertCount(1, $this->superAdmin()->posts);
    }

    public function testItCanCancel(): void
    {
        $this->actingAs($this->superAdmin());

        Livewire::test('posts.form')
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('redirectBack');

        $this->assertDatabaseMissing('posts', ['title' => 'New Post']);
        $this->assertCount(0, $this->superAdmin()->posts);
    }
}
