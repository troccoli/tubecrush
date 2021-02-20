<div>
    <form wire:submit.prevent="submit">
        <!-- Title -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="title" value="{{ __('Title') }}"/>
            <x-jet-input id="title" type="text" class="mt-2 block w-full" wire:model.defer="title"
                         autocomplete="title"/>
            <x-jet-input-error for="title" class="mt-2" dusk="title-error"/>
        </div>

        <!-- Line -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="line" value="{{ __('Line') }}"/>
            <x-line-select></x-line-select>
            <x-jet-input-error for="line" class="" dusk="line-error"/>
        </div>

        <!-- Content -->
        <div class="mb-6">
            <x-jet-label for="content" value="{{ __('Content') }}"/>
            <x-jet-textarea id="content" class="mt-2 block w-full" wire:model.defer="content" rows="10"/>
            <x-jet-input-error for="content" class="mt-2" dusk="content-error"/>
        </div>

        <!-- Photo -->
        <div class="mb-6">
            <x-upload-photo-button></x-upload-photo-button>
            <x-jet-input-error for="photo" class="mt-2" dusk="photo-error"/>
            @unless($errors->has('photo'))
                <div class="mt-4">
                    @if ($photo)
                        <img dusk="photo-image" src="{{ $photo->temporaryUrl() }}" alt="temp">
                    @endif
                </div>
            @endunless
        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-end">
            <x-jet-secondary-button dusk="cancel-button" wire:click="cancelCreate"
                                    class="w-full justify-center md:w-auto">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-button dusk="submit-button" class="w-full justify-center md:w-auto">
                <svg wire:loading wire:target="submit" dusk="submit-loading-icon" class="animate-spin w-4 h-4 mr-1"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                {{ __('Create') }}
            </x-jet-button>
        </div>
    </form>
</div>
