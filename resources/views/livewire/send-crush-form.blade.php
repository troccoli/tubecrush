@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ mix('css/select2.css') }}">
@endpush
<div>
    <form wire:submit.prevent="submit">
        <!-- Line -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="line" value="{{ __('Line') }}"/>
            <x-line-select></x-line-select>
            <x-jet-input-error for="line" class="" dusk="line-error"/>
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

        <!-- Photo Credit -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="photo-credit" value="{{ __('Photo submitted by') }}"/>
            <x-jet-input id="photo-credit" type="text" class="mt-2 block w-full" wire:model.defer="photoCredit"/>
            <x-jet-input-error for="photoCredit" class="mt-2" dusk="photo-credit-error"/>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-end">
            <x-jet-secondary-button dusk="cancel-button"
                                    wire:click="cancelSubmission"
                                    class="w-full justify-center md:w-auto">
                {{ __('Cancel') }}
            </x-jet-secondary-button>
            <x-jet-button dusk="submit-button" class="w-full justify-center md:w-auto">
                <x-heroicons-s-refresh dusk="submit-loading-icon"
                                       wire:loading
                                       wire:target="submit"
                                       class="animate-reverse-spin w-4 h-4 mr-1"/>
                {{ __('Send') }}
            </x-jet-button>
        </div>
    </form>
</div>
