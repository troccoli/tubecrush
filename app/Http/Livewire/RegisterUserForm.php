<?php

namespace App\Http\Livewire;

use App\Enums\UserRoles;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class RegisterUserForm extends Component
{
    public string $name = '';
    public string $email = '';

    protected array $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|unique:users,email',
    ];

    public function registerUser(): void
    {
        $data = $this->validate();

        $data['password'] = Hash::make(Str::random());

        $user = User::create($data);
        $user->assignRole(UserRoles::Editor->value);

        $this->emit('userRegistered');
        $this->reset();

        event(new Registered($user));
    }

    public function render(): View
    {
        return view('livewire.register-user-form');
    }
}
