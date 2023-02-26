<x-app-layout>
    <x-slot name="header">
        <x-tubecrush-banner/>
        <h1 class="text-gray-800 text-3xl md:text-5xl font-semibold text-center mt-8">Posts for tag {{ $name }}</h1>
    </x-slot>

    <livewire:posts.list-posts :tagId="$id"/>

</x-app-layout>
