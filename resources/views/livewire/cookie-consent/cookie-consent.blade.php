<div>
    @if($askForConsent)
        @include('livewire.cookie-consent.consent-modal')
        @include('livewire.cookie-consent.cookie-policy-modal')
    @endif
</div>
