@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{ mix('css/select2.css') }}">
@endpush
<div>
    <form wire:submit.prevent="submit">
        <!-- Title -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="title" value="{{ __('Title') }}"/>
            <x-jet-input id="title" type="text" class="mt-2 block w-full" wire:model.defer="title"
                         autocomplete="title"/>
            <x-jet-input-error for="title" class="mt-2" dusk="title-error" cy="title-error"/>
        </div>

        <!-- Line -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="line" value="{{ __('Line') }}"/>
            <x-line-select></x-line-select>
            <x-jet-input-error for="line" class="" dusk="line-error" cy="line-error"/>
        </div>

        <!-- Content -->
        <div class="mb-6">
            <x-jet-label for="content" value="{{ __('Content') }}"/>
            <x-jet-textarea id="content" class="mt-2 block w-full" wire:model.defer="content" rows="10"/>
            <x-jet-input-error for="content" class="mt-2" dusk="content-error" cy="content-error"/>
        </div>

        <!-- Photo -->
        <div class="mb-6">
            <x-upload-photo-button></x-upload-photo-button>
            <x-jet-input-error for="photo" class="mt-2" dusk="photo-error" cy="photo-error"/>
            @unless($errors->has('photo'))
                <div class="mt-4">
                    @if ($photo)
                        <img dusk="photo-image" cy="photo-image" src="{{ $photo->temporaryUrl() }}" alt="temp">
                    @endif
                </div>
            @endunless
        </div>

        <!-- Photo Credit -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="photo-credit" value="{{ __('Photo submitted by') }}"/>
            <x-jet-input id="photo-credit" type="text" class="mt-2 block w-full" wire:model.defer="photoCredit"/>
            <x-jet-input-error for="photoCredit" class="mt-2" dusk="photo-credit-error" cy="photo-credit-error"/>
        </div>

        <!-- Tags -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="tags" value="{{ __('Tags') }}"/>
            <div wire:ignore class="mt-2" dusk="tags-select" cy="tags-select">
                <select id="tags" class="form-input block w-full " name="tags" multiple>
                    @foreach($availableTags as $tag)
                        <option value="{{ $tag['id'] }}">{{ $tag['text'] }}</option>
                    @endforeach
                </select>
            </div>
            <x-jet-input-error for="tags" class="mt-2" dusk="tags-error" cy="tags-error"/>
        </div>

        <!-- Buttons -->
        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-end">
            <x-jet-secondary-button dusk="cancel-button" cy="cancel-button" wire:click="cancelCreate"
                                    class="w-full justify-center md:w-auto">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-button dusk="submit-button" cy="submit-button" class="w-full justify-center md:w-auto">
                <x-heroicons-s-arrow-path wire:loading wire:target="submit" dusk="submit-loading-icon"
                                          cy="submit-loading-icon" class="animate-spin w-4 h-4 mr-1"/>
                {{ __('Create') }}
            </x-jet-button>
        </div>
    </form>
</div>
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tags')
                .select2({
                    placeholder: 'Start typing to search for tags'
                })
                .on('change', function (e) {
                    var data = $('#tags').select2("val");
                @this.set('tags', data);
                });
        });
    </script>
@endpush
