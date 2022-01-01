<?php

namespace App\Http\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Footer extends Component
{
    public string $phpVersion;
    public string $laravelVersion;
    public string $livewireVersion;
    public string $tailwindcssVersion;

    public function mount()
    {
        $this->phpVersion = phpversion();
        $this->laravelVersion = app()->version();
        $this->livewireVersion = '2.8.2';
        $this->tailwindcssVersion = '3.0.8';
    }

    public function render(): View
    {
        return view('livewire.footer');
    }
}
