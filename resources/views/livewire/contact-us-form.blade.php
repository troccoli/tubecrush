<div>
    <form wire:submit.prevent="contactUs">
        <div class="md:flex">
            <!-- Name -->
            <div class="mb-6 md:w-full md:mr-6">
                <x-jet-label for="name" value="{{ __('Name') }}"/>
                <x-jet-input id="name" type="text" class="mt-2 block w-full" wire:model="name"
                             autocomplete="name"/>
                <x-jet-input-error for="name" class="mt-2"/>
            </div>

            <!-- Email -->
            <div class="mb-6 md:w-full">
                <x-jet-label for="email" value="{{ __('Email') }}"/>
                <x-jet-input id="email" type="email" class="mt-2 block w-full" wire:model="email"/>
                <x-jet-input-error for="email" class="mt-2"/>
            </div>
        </div>
        <!-- Message -->
        <div class="mb-6">
            <x-jet-label for="message" value="{{ __('Message') }}"/>
            <x-jet-textarea id="message" class="mt-2 block w-full" wire:model="message" rows="10"/>
            <x-jet-input-error for="message" class="mt-2"/>
            <div></div>
        </div>

        <div>
            <x-jet-button class="w-full justify-center md:w-auto">{{ __('Send') }}</x-jet-button>
        </div>

        <x-jet-action-message on="messageSent" delay="10000" class="text-left bg-green-100 p-3 mt-6 w-full rounded inline-flex">
            <svg class="h-5 w-5 mr-1 d-inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="green">
                <path fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"/>
            </svg>
            {{ __('Your message has been sent.') }}
        </x-jet-action-message>

    </form>
</div>
