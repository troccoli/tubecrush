<div x-data="{ open: @entangle('openConsentModal') }" x-show="open" dusk="cookie-consent-modal"
     cy="cookie-consent-modal"
     class="fixed z-10 w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="absolute w-full h-full bg-gray-900 opacity-50"></div>

    <div class="bg-white w-auto mx-3 sm:mx-0 rounded shadow-lg z-50 overflow-y-auto">
        <div class="py-4 text-left px-6">
            <!--Title-->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-900">
                <x-heroicons-o-speakerphone class="h-8 w-8 text-white"/>
            </div>

            <!--Body-->
            <div class="mt-5 text-center text-gray-500 space-y-2 leading-snug">
                <p>{{  __('Your experience on this site will be improved by allowing cookies.') }}</p>
                <div>
                    {{ __('Learn mode about our cookies') }}
                    <button wire:click="toggleCookieModal" class="hover:text-blue-500"
                            dusk="cookie-consent-cookie-policy-button" cy="cookie-consent-cookie-policy-button">
                        <x-heroicons-o-information-circle class="h-5 w-5 inline-block"/>
                    </button>
                </div>
            </div>

            <!--Footer-->
            <div class="mt-5 flex flex-col sm:flex-row space-y-2 sm:space-x-2 sm:space-y-0">
                <button wire:click="refuseConsent" dusk="cookie-consent-refuse-button" cy="cookie-consent-refuse-button"
                        class="w-full sm:w-1/2 inline-flex justify-center border border-gray-300 rounded-md shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue'">
                    {{ __('Refuse cookies') }}
                </button>
                <button wire:click="giveConsent" dusk="cookie-consent-accept-button" cy="cookie-consent-accept-button"
                        class="w-full sm:w-1/2 inline-flex justify-center border border-transparent rounded-md shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Accept cookies') }}
                </button>
            </div>
        </div>
    </div>
</div>
