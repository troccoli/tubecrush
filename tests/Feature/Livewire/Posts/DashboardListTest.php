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
            ->assertSet('confirmingDeletingId', null)
            ->call('confirmAction', $post->getKey(), 'delete')
            ->assertSet('confirmingDeletingId', $post->getKey())
            ->call('deletePost');

        $this->assertSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testAskForConfirmationWhenPublishingAPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->draft()->create();

        Livewire::test('posts.dashboard-list')
            ->assertSet('confirmingPublishingId', null)
            ->call('confirmAction', $post->getKey(), 'publish')
            ->assertSet('confirmingPublishingId', $post->getKey())
            ->call('publishPost');

        $post->refresh();
        $this->assertFalse($post->isDraft());
    }

    public function testAskForConfirmationWhenUnpublishingAPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->assertSet('confirmingUnpublishingId', null)
            ->call('confirmAction', $post->getKey(), 'unpublish')
            ->assertSet('confirmingUnpublishingId', $post->getKey())
            ->call('unpublishPost');

        $post->refresh();
        $this->assertTrue($post->isDraft());
    }

    public function testCanCancelDeletion(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post->getKey(), 'delete')
            ->assertSet('confirmingDeletingId', $post->getKey())
            ->call('keepPost')
            ->assertSet('confirmingDeletingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testCanCancelPublishing(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->draft()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post->getKey(), 'publish')
            ->assertSet('confirmingPublishingId', $post->getKey())
            ->call('keepPost')
            ->assertSet('confirmingPublishingId', null);

        $post->refresh();
        $this->assertTrue($post->isDraft());
    }

    public function testCanCancelUnpublishing(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post->getKey(), 'unpublish')
            ->assertSet('confirmingUnpublishingId', $post->getKey())
            ->call('keepPost')
            ->assertSet('confirmingUnpublishingId', null);

        $post->refresh();
        $this->assertFalse($post->isDraft());
    }

    public function testItDoesNotDeleteNonExistingPosts(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post */
        $post = Post::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post->getKey() + 1, 'delete')
            ->assertSet('confirmingId', $post->getKey() + 1)
            ->call('deletePost')
            ->assertSet('confirmingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post->getKey()]);
    }

    public function testItDeletesTheLastConfirmedPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post1 */
        $post1 = Post::factory()->create();
        /** @var Post $post2 */
        $post2 = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post1->getKey(), 'delete')
            ->assertSet('confirmingDeletingId', $post1->getKey())
            ->call('confirmAction', $post2->getKey(), 'delete')
            ->assertSet('confirmingDeletingId', $post2->getKey())
            ->call('deletePost')
            ->assertSet('confirmingDeletingId', null);

        $this->assertNotSoftDeleted('posts', ['id' => $post1->getKey()]);
        $this->assertSoftDeleted('posts', ['id' => $post2->getKey()]);
    }

    public function testItPublishesTheLastConfirmedPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post1 */
        $post1 = Post::factory()->draft()->create();
        /** @var Post $post2 */
        $post2 = Post::factory()->draft()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post1->getKey(), 'publish')
            ->assertSet('confirmingPublishingId', $post1->getKey())
            ->call('confirmAction', $post2->getKey(), 'publish')
            ->assertSet('confirmingPublishingId', $post2->getKey())
            ->call('publishPost')
            ->assertSet('confirmingPublishingId', null);

        $post1->refresh();
        $post2->refresh();
        $this->assertTrue($post1->isDraft());
        $this->assertFalse($post2->isDraft());
    }

    public function testItUnpublishesTheLastConfirmedPost(): void
    {
        $this->actingAs($this->superAdmin());

        /** @var Post $post1 */
        $post1 = Post::factory()->create();
        /** @var Post $post2 */
        $post2 = Post::factory()->create();

        Livewire::test('posts.dashboard-list')
            ->call('confirmAction', $post1->getKey(), 'unpublish')
            ->assertSet('confirmingUnpublishingId', $post1->getKey())
            ->call('confirmAction', $post2->getKey(), 'unpublish')
            ->assertSet('confirmingUnpublishingId', $post2->getKey())
            ->call('unpublishPost')
            ->assertSet('confirmingUnpublishingId', null);

        $post1->refresh();
        $post2->refresh();
        $this->assertFalse($post1->isDraft());
        $this->assertTrue($post2->isDraft());
    }
}
