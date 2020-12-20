<?php

namespace App\Http\Livewire;

use App\Models\User;
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

    public function registerUser()
    {
        $data = $this->validate();

        $data['password'] = Hash::make(Str::random());

        $user = User::create($data);
        $user->assignRole('Editor');

        $this->emit('userRegistered');
        $this->resetInputFields();
    }

    public function render(): View
    {
        return view('livewire.register-user-form');
    }

    private function resetInputFields(): void
    {
        $this->name = '';
        $this->email = '';
    }
}
