<x-app-layout>
    <x-slot name="header">
        <x-banner/>
    </x-slot>

    <div class="flex flex-col">
        <livewire:posts.single-post :post="$post" :wire:key="$post->getKey()"/>
    </div>

</x-app-layout>
