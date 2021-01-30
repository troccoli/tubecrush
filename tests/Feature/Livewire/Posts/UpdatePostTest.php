<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Post;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class UpdatePostTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create();

        $this->actingAs($this->superAdmin())
            ->get(route('posts.update', ['postId' => $post->getId()]))
            ->assertSeeLivewire('posts.edit-post');
    }

    public function testTheTileIsRequiredAndCannotBeLongerThan50Characters(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create();

        Livewire::test('posts.edit-post', ['postId' => $post->getId()])
            ->set('title', '')
            ->call('submit')
            ->assertHasErrors(['title' => 'required'])
            ->set('title', Str::random(51))
            ->call('submit')
            ->assertHasErrors(['title' => 'max']);
    }

    public function testTheContentIsRequiredAndMustBeAtLeast10CharactersLongAndNoMoreThan2000(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create();

        Livewire::test('posts.edit-post', ['postId' => $post->getId()])
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

    public function testItUpdatesAPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create(['title' => 'Old Post']);

        Livewire::test('posts.edit-post', ['postId' => $post->getId()])
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('submit');

        $this->assertDatabaseHas('posts', ['title' => 'New Post']);
        $this->assertDatabaseMissing('posts', ['title' => 'Old Posts']);
        $this->assertCount(1, $this->superAdmin()->posts);
    }

    public function testItCanCancel(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create(['title' => 'Old Post']);

        Livewire::test('posts.edit-post', ['postId' => $post->getId()])
            ->set('title', "New Post")
            ->set('content', 'Amazing content for this new post')
            ->call('cancelEdit');

        $this->assertDatabaseMissing('posts', ['title' => 'New Post']);
        $this->assertDatabaseHas('posts', ['title' => 'Old Post']);
        $this->assertCount(1, $this->superAdmin()->posts);
    }
}
