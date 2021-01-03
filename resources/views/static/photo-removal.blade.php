<x-app-layout>
    <x-slot name="header">
        <x-banner/>
    </x-slot>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 text-gray-700 leading-relaxed">
        <h2 class="mb-6 text-2xl sm:text-3xl">Photo Removal Request</h2>
        <p class="mb-4">
            We understand that not everyone appreciates being found attractive by other people and having their picture
            taken so have included the ability to have your photo removed from our site.
        </p>
        <p class="mb-4">
            If your image is on our site and you don’t want it to be just drop us an email and include a recent photo of
            yourself and a link to the page that features your image and we will verify it is you and remove it –
            <a href="mailto:{{ config('mail.from.address') }}" class="underline hover:text-red-600">{{ config('mail.from.address') }}</a>
        </p>
        <p>
            Once we have received the request we will email a reply within 24 hours to request either a link to, or an
            attachment of a recent photograph that demonstrates that you are the person featured on the website. The
            reason we do this is to protect your identity and verify that you are the person/s in the image. Also there
            are many spam bots that trawl websites and complete web forms such as this with spamming data and we have
            found this verification method to be the most robust.
        </p>
    </div>
</x-app-layout>
