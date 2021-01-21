<?php

namespace Tests\Feature;

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
        $this->get(route('posts'))
            ->assertRedirect(route('login'));

        $this->actingAs($this->superAdmin())
            ->get(route('posts'))
            ->assertSuccessful();

        $this->actingAs($this->editor())
            ->get(route('posts'))
            ->assertSuccessful();
    }
}
