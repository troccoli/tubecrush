<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;

class AuthenticationTest extends TestCase
{
    public function testAccessingLoginPage(): void
    {
        $this->get(route('login'))
            ->assertStatus(200);

        $this->actingAs(User::factory()->create())
            ->get(route('login'))
            ->assertRedirect(route('home'));
    }

    public function testAccessingRegistrationPage(): void
    {
        $this->get(route('register'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('register'))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('register'))
            ->assertForbidden();
    }

    public function testAccessingDashboardPage(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('dashboard'))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('dashboard'))
            ->assertSuccessful();
    }

    public function testAccessingPostsPage(): void
    {
        $this->get(route('posts.list'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('posts.list'))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('posts.list'))
            ->assertSuccessful();
    }

    public function testAccessingPostsCreatePage(): void
    {
        $this->get(route('posts.create'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('posts.create'))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('posts.create'))
            ->assertSuccessful();
    }

    public function testAccessingPostsEditPage(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for($this->superAdmin(), 'author')->create();

        $this->get(route('posts.update', ['postId' => $post->getId()]))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('posts.update', ['postId' => $post->getId()]))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('posts.update', ['postId' => $post->getId()]))
            ->assertSuccessful();
    }
}
