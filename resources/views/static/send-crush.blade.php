<x-app-layout>
    <x-slot name="header">
        <x-tubecrush-banner/>
    </x-slot>

    <div class="flex flex-col">
        <div class="w-full md:max-w-3xl px-4 sm:px-6 lg:px-8 mx-auto ">
            <livewire:send-crush-form/>
        </div>
    </div>
</x-app-layout>
