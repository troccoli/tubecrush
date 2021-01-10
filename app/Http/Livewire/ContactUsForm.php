<?php

namespace App\Http\Livewire;

use App\Events\SomeoneHasContactedUs;
use Illuminate\Contracts\View\View;
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

    /**
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function contactUs(): void
    {
        $data = $this->validate();

        $this->reset();
        $this->emit('messageSent');

        event(new SomeoneHasContactedUs($data['name'], $data['email'], $data['message']));
    }

    public function render(): View
    {
        return view('livewire.contact-us-form');
    }
}
