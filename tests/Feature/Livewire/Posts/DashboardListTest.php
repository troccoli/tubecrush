<?php

namespace Tests\Feature\Livewire\Posts;

use App\Models\Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class DashboardListTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('posts.list'))
            ->assertSeeLivewire('posts.dashboard-list');
    }

    public function testAskForConfirmationWhenDeletingAPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->assertSet('confirmingId', null)
            ->call('confirmDelete', $post->getKey())
            ->assertSet('confirmingId', $post->getKey())
            ->call('deletePost');

        $this->assertSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testCanCancelDeletion(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post->getKey())
            ->assertSet('confirmingId', $post->getKey())
            ->call('keepPost')
            ->assertSet('confirmingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testItDoesNotDeleteNonExistingPosts(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post->getKey() + 1)
            ->assertSet('confirmingId', $post->getKey() + 1)
            ->call('deletePost')
            ->assertSet('confirmingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testItDeleteTheLastConfirmedPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post1 */
        $post1 = Post::factory()->create();
        /** @var Post $post2 */
        $post2 = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post1->getKey())
            ->assertSet('confirmingId', $post1->getKey())
            ->call('confirmDelete', $post2->getKey())
            ->assertSet('confirmingId', $post2->getKey())
            ->call('deletePost')
            ->assertSet('confirmingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post1->getKey()]);
        $this->assertSoftDeleted('posts', ['id' => $post2->getKey()]);
    }
}
