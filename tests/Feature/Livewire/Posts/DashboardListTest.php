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
        $post = Post::factory()->bySuperAdmin()->create();

        Livewire::test('posts.dashboard-list')
            ->assertSet('confirmingId', null)
            ->call('confirmDelete', $post->getId())
            ->assertSet('confirmingId', $post->getId())
            ->call('deletePost');

        $this->assertSoftDeleted('posts', ['id' => $post->getId()]);
    }

    public function testCanCancelDeletion(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->bySuperAdmin()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post->getId())
            ->assertSet('confirmingId', $post->getId())
            ->call('keepPost')
            ->assertSet('confirmingId', null);

        $this->assertDatabaseHas('posts', ['id' => $post->getId()]);
    }

    public function testItDoesNotDeleteNonExistingPosts(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->bySuperAdmin()->create();

        $this->expectException(ModelNotFoundException::class);

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post->getId() + 1)
            ->assertSet('confirmingId', $post->getId() + 1)
            ->call('deletePost')
            ->assertSet('confirmingId', null);

        $this->assertDatabaseHas('posts', ['id' => $post->getId()]);
    }

    public function testItDeleteTheLastConfirmedPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post1 */
        $post1 = Post::factory()->bySuperAdmin()->create();
        /** @var Post $post2 */
        $post2 = Post::factory()->bySuperAdmin()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmDelete', $post1->getId())
            ->assertSet('confirmingId', $post1->getId())
            ->call('confirmDelete', $post2->getId())
            ->assertSet('confirmingId', $post2->getId())
            ->call('deletePost')
            ->assertSet('confirmingId', null);

        $this->assertDatabaseHas('posts', ['id' => $post1->getId()]);
        $this->assertSoftDeleted('posts', ['id' => $post2->getId()]);
    }
}
