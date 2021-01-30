<div>
    <form wire:submit.prevent="submit">
        <!-- Title -->
        <div class="mb-6 md:w-full md:mr-6">
            <x-jet-label for="title" value="{{ __('Title') }}"/>
            <x-jet-input id="title" type="text" class="mt-2 block w-full" wire:model="title"
                         autocomplete="title"/>
            <x-jet-input-error for="title" class="mt-2"/>
        </div>

        <!-- Content -->
        <div class="mb-6">
            <x-jet-label for="content" value="{{ __('Content') }}"/>
            <x-jet-textarea id="content" class="mt-2 block w-full" wire:model="content" rows="10"/>
            <x-jet-input-error for="content" class="mt-2"/>
        </div>

        <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-2 justify-end">
            <x-jet-secondary-button wire:click="cancelEdit" class="w-full justify-center md:w-auto">{{ __('Cancel') }}</x-jet-secondary-button>
            <x-jet-button class="w-full justify-center md:w-auto">{{ __('Update') }}</x-jet-button>
        </div>
    </form>
</div>
