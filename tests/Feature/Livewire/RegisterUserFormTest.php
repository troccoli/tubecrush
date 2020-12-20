<?php

namespace Tests\Feature\Livewire;

use App\Models\User;
use Illuminate\Support\Str;
use Livewire\Livewire;
use Tests\Feature\TestCase;

class RegisterUserFormTest extends TestCase
{
    public function testTheComponentIsRendered(): void
    {
        $this->actingAs($this->superAdmin())
            ->get(route('register'))
            ->assertSeeLivewire('register-user-form');
    }

    public function testTheUserIsCreatedWithTheEditorRole(): void
    {
        Livewire::test('register-user-form')
            ->set('name', 'Paul')
            ->set('email', 'paul@example.com')
            ->call('registerUser')
            ->assertEmitted('userRegistered');

        $user = User::whereEmail('paul@example.com')->first();

        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('Editor'));
    }

    public function testTheNameIsMandatoryAndCannotBeLongerThan255Characters(): void
    {
        Livewire::test('register-user-form')
            ->set('email', 'paul@example.com')
            ->call('registerUser')
            ->assertHasErrors(['name' => 'required']);
        $this->assertNull(User::whereEmail('paul@example.com')->first());

        Livewire::test('register-user-form')
            ->set('name', Str::random(256))
            ->set('email', 'paul@example.com')
            ->call('registerUser')
            ->assertHasErrors(['name' => 'max']);
        $this->assertNull(User::whereEmail('paul@example.com')->first());
    }

    public function testTheEmailIsRequiredAndMustBeAValidEmailAndMustNotHaveBeenUsed(): void
    {
        Livewire::test('register-user-form')
            ->set('name', 'Paul')
            ->call('registerUser')
            ->assertHasErrors(['email' => 'required']);
        $this->assertNull(User::whereName('Paul')->first());

        Livewire::test('register-user-form')
            ->set('name', 'Paul')
            ->set('email', 'email')
            ->call('registerUser')
            ->assertHasErrors(['email' => 'email']);
        $this->assertNull(User::whereName('Paul')->first());

        Livewire::test('register-user-form')
            ->set('name', 'Paul')
            ->set('email', 'editor@example.com')
            ->call('registerUser')
            ->assertHasErrors(['email' => 'unique']);
        $this->assertNull(User::whereName('Paul')->first());
    }
}
