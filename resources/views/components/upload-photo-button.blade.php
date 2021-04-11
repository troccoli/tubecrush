<x-jet-label for="photo" dusk="upload-photo-button"
             class="relative md:static flex md:inline-flex justify-center p-2 pr-4 w-full md:w-auto border border-transparent rounded bg-indigo-600 text-indigo-100 hover:bg-indigo-700 cursor-pointer transition duration-300">
    <x-jet-input id="photo" type="file" class="hidden" wire:model="photo"/>
    <span class="absolute md:static left-0 inset-y-0 flex items-center pl-3 md:pl-0">
        <x-heroicons-s-upload wire:loading.remove wire:target="photo" class="w-5 h-5 mr-1"/>
        <x-heroicons-s-refresh wire:loading wire:target="photo" dusk="photo-loading-icon" class="animate-reverse-spin w-5 h-5 mr-1" />
    </span>
    Upload a photo
</x-jet-label>
