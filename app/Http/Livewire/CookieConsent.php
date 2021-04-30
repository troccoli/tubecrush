<?php

namespace App\Http\Livewire;

use Livewire\Component;

class CookieConsent extends Component
{
    public bool $askForConsent;

    public bool $openConsentModal;
    public bool $openCookieModal = false;

    public function mount(\App\Services\CookieConsent $service)
    {
        $this->askForConsent = !$service->cookieExists();
        $this->openConsentModal = true;
    }

    public function toggleCookieModal()
    {
        $this->openCookieModal = !$this->openCookieModal;
        $this->openConsentModal = !$this->openConsentModal;
    }

    public function giveConsent(\App\Services\CookieConsent $service)
    {
        $service->giveConsent();

        $this->openConsentModal = false;
        $this->askForConsent = false;
    }

    public function refuseConsent(\App\Services\CookieConsent $service)
    {
        $service->refuseConsent();

        $this->openConsentModal = false;
        $this->askForConsent = false;
    }

    public function render()
    {
        return view('livewire.cookie-consent.cookie-consent');
    }
}
