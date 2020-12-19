<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

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

        $this->actingAs(User::factory()->create()->assignRole('Super Admin'))
            ->get(route('register'))
            ->assertSuccessful();

        $this->actingAs(User::factory()->create()->assignRole('Editor'))
            ->get(route('register'))
            ->assertForbidden();
    }

    public function testAccessingDashboardPage(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect(route('login'));

        $this->actingAs(User::factory()->create()->assignRole('Super Admin'))
            ->get(route('dashboard'))
            ->assertSuccessful();

        $this->actingAs(User::factory()->create()->assignRole('Editor'))
            ->get(route('dashboard'))
            ->assertSuccessful();
    }
}
