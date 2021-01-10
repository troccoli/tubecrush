<?php

namespace App\Http\Livewire;

use App\Events\SomeoneHasContactedUs;
use App\Mail\ContactUsMessage;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Livewire\Component;

class ContactUsForm extends Component
{
    public string $name = '';
    public string $email = '';
    public string $message = '';

    protected array $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email',
        'message' => 'required|min:10|max:500',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function contactUs()
    {
        $data = $this->validate();

        $this->reset();
        $this->emit('messageSent');

        event(new SomeoneHasContactedUs($data['name'], $data['email'], $data['message']));
    }

    public function render()
    {
        return view('livewire.contact-us-form');
    }
}
