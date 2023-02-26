<x-form-section submit="registerUser">
    <x-slot name="title">
        {{ __('Register a new user') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Enter the details of the new user. They will be added as Editors with a random password.') }}
    </x-slot>
    <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Name') }}"/>
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model.defer="name"
                     autocomplete="name"/>
            <x-input-error for="name" class="mt-2"/>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Email') }}"/>
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="email"/>
            <x-input-error for="email" class="mt-2"/>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-button>Register</x-button>
        </div>
    </x-slot>
    <x-slot name="actions">
        <x-action-message on="userRegistered" class="text-left bg-green-100 p-3 w-full rounded inline-flex">
            <x-heroicons-s-check-circle class="h-5 w-5 mr-1 d-inline" fill="green"/>
            The user has been registered.
        </x-action-message>
    </x-slot>
</x-form-section>
