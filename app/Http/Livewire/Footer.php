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

    public function __construct()
    {
        $this->phpVersion = phpversion();
        $this->laravelVersion = app()->version();
        $this->livewireVersion = '2.3.5';
        $this->tailwindcssVersion = '1.9.6';
    }

    public function render(): View
    {
        return view('livewire.footer');
    }
}
