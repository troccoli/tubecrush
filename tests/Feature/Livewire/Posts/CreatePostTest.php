<?php

namespace Tests\Feature\Livewire\Posts;

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

    public function testTheTileIsRequiredAndCannotBeLongerThan50Characters(): void
    {
        Livewire::test('posts.create-post')
            ->call('submit')
            ->assertHasErrors(['title' => 'required'])
            ->set('title', Str::random(51))
            ->call('submit')
            ->assertHasErrors(['title' => 'max']);
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

    public function testItCreatesANewPost(): void
    {
        $this->actingAs($this->superAdmin());

        Livewire::test('posts.create-post')
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('submit');

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertCount(1, $this->superAdmin()->posts);
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
