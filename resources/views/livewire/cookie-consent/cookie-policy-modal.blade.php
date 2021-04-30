<div x-data="{ open: @entangle('openCookieModal') }" x-show="open" dusk="cookie-policy-modal"
     class="fixed z-10 w-full h-full top-0 left-0 flex items-center justify-center">
    <div class="absolute w-full h-full bg-gray-900 opacity-50 sm:bg-yellow-500"></div>

    <div class="bg-white w-auto mx-3 sm:mx-0 rounded shadow-lg z-50 overflow-y-auto">
        <div class="py-4 text-left px-6">
            <!--Title-->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-gray-900">
                <x-heroicons-o-speakerphone class="h-8 w-8 text-white"/>
            </div>

            <!--Body-->
            <div class="mt-5 text-center text-gray-500 space-y-2 leading-snug">
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-headline">
                    Cookie Statement
                </h3>
                <p>{{  __('Cookies are used to store your personal votes for posts.') }}</p>
            </div>

            <!--Footer-->
            <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-1 sm:gap-3 sm:grid-flow-row-dense">
                <button wire:click="toggleCookieModal" dusk="cookie-policy-close-button"
                        class="mb-2 w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-white hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400">
                    {{ __('Close') }}
                </button>
            </div>
        </div>
    </div>
</div>
