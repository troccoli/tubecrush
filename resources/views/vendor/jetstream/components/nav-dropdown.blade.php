@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'hidden md:items-center md:ml-10 text-gray-500 md:flex border-b-2 border-indigo-400 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
        : 'hidden md:items-center md:ml-10 text-gray-500 md:flex border-b-2 border-transparent hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition';
@endphp

<div {{ $attributes->merge(['class' => $classes, 'dusk' => '']) }} >
    <x-jet-dropdown align="left">
        <x-slot name="trigger">
            <button
                class="flex items-center text-sm font-medium pt-1">
                <div>{{ $name }}</div>

                <div class="ml-1">
                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
            </button>
        </x-slot>

        <x-slot name="content">
            {{ $slot }}
        </x-slot>
    </x-jet-dropdown>
</div>
