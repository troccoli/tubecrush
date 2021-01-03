<x-app-layout>
    <x-slot name="header">
        <x-banner/>
    </x-slot>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-gray-700 leading-relaxed">
        <h2 class="mb-6 text-2xl sm:text-3xl">Press Enquiries</h2>
        <p class="mb-4">
            If you wish to speak to a member of our team for press coverage, please either email us on
            <a href="mailto:{{ config('mail.from.address') }}" class="underline hover:text-red-600">{{ config('mail.from.address') }}</a> or complete the
            form below and someone will get back to you as soon as possible. For
            immediate response you can also telephone +1 (202) 4450686
        </p>
    </div>
</x-app-layout>
