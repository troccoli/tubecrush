<div>
    <form wire:submit.prevent="submit">
        <!-- Title -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="title" value="{{ __('Title') }}"/>
            <x-jet-input id="title" type="text" class="mt-2 block w-full" wire:model="title"
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
            <x-jet-textarea id="content" class="mt-2 block w-full" wire:model="content" rows="10"/>
            <x-jet-input-error for="content" class="mt-2" dusk="content-error"/>
        </div>

        <!-- Photo -->
        <div class="mb-6">
            <x-jet-label for="photo" dusk="upload-photo-button" class="inline-flex items-center p-2 pr-4 w-full md:w-auto border border-transparent rounded bg-indigo-600 text-indigo-100 hover:bg-indigo-700 cursor-pointer transition duration-300">
                <x-jet-input id="photo" type="file" class="hidden" wire:model="photo"/>
                <span class="absolute md:static left-0 inset-y-0 flex items-center pl-3 md:pl-0">
                    <svg wire:loading.remove wire:target="photo" class="w-5 h-5 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <svg wire:loading wire:target="photo" dusk="photo-loading-icon" class="animate-spin w-5 h-5 mr-1"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                              d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </span>
                Upload a photo
            </x-jet-label>
            <x-jet-input-error for="photo" class="mt-2" dusk="photo-error"/>
            <div class="mt-4">
                @if ($errors->has('photo') || !$photo)
                    <img dusk="photo-image" src="{{ \Illuminate\Support\Facades\Storage::url($post->getPhoto()) }}" alt="Cover photo">
                @else
                    <img dusk="photo-image" src="{{ $photo->temporaryUrl() }}" alt="temp">
                @endif
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-end">
            <x-jet-secondary-button dusk="cancel-button" wire:click="cancelEdit" class="w-full justify-center md:w-auto">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-button dusk="submit-button" class="w-full justify-center md:w-auto">
                <svg wire:loading wire:target="submit" dusk="submit-loading-icon" class="animate-spin w-4 h-4 mr-1"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                {{ __('Update') }}
            </x-jet-button>
        </div>
    </form>
</div>
