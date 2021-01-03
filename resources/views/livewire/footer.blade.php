<div class="flex items-end w-full bg-white" dusk="main-footer">

    <footer class="w-full text-gray-700 bg-gray-100 body-font">
        <div
            class="container flex flex-col flex-wrap px-5 py-10 mx-auto md:items-center lg:items-start md:flex-row md:flex-no-wrap">
            <div class="flex-shrink-0 w-64 mx-auto text-center md:mx-0 md:text-left">
                <p class="mt-2 text-sm text-gray-500">TubeCrush™</p>
                <div class="mt-4">
                    <span class="inline-flex justify-center mt-2 sm:ml-auto sm:mt-0 sm:justify-start">
                        {{-- Facebook --}}
                        <a class="text-gray-500 cursor-pointer hover:text-gray-700"
                           href="https://www.facebook.com/TubeCrush">
                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 class="w-5 h-5" viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"></path>
                            </svg>
                        </a>
                        {{-- Twitter--}}
                        <a class="ml-3 text-gray-500 cursor-pointer hover:text-gray-700"
                           href="https://twitter.com/tubecrush">
                            <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                 class="w-5 h-5" viewBox="0 0 24 24">
                                <path
                                    d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z">
                                </path>
                            </svg>
                        </a>
                        {{-- Instagram --}}
                        <a class="ml-3 text-gray-500 cursor-pointer hover:text-gray-700"
                           href="https://www.instagram.com/officialtubecrush/">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                 stroke-width="2" class="w-5 h-5" viewBox="0 0 24 24">
                                <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                                <path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01"></path>
                            </svg>
                        </a>
                    </span>
                </div>
            </div>
            <div class="flex flex-wrap flex-grow mt-10 -mb-10 text-center md:pl-20 md:mt-0 md:text-left">
                <div class="w-full px-4 lg:w-1/4 md:w-1/2">
                    <h2 class="mb-3 text-sm font-medium tracking-widest text-gray-900 uppercase title-font">{{__('About')}}</h2>
                    <nav class="mb-10 list-none">
                        <ul>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="{{ route('about-us') }}">{{__('About us')}}</a>
                            </li>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="{{ route('guidelines') }}">{{__('Photo guidelines')}}</a>
                            </li>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="{{ route('legal') }}">{{__('Legal')}}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="w-full px-4 lg:w-1/4 md:w-1/2">
                    <h2 class="mb-3 text-sm font-medium tracking-widest text-gray-900 uppercase title-font">{{__('Contact')}}</h2>
                    <nav class="mb-10 list-none">
                        <ul>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="#">{{__('Contact us')}}</a>
                            </li>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="{{ route('photo-removal') }}">{{__('Photo removal')}}</a>
                            </li>
                            <li class="mt-3">
                                <a class="text-gray-500 cursor-pointer hover:text-gray-900"
                                   href="#">{{__('Press enquiries')}}</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
        <div class="bg-gray-300">
            <div class="container px-5 py-4 mx-auto">
                <p class="text-sm text-gray-700 capitalize text-center">© {{ date('Y') }} All rights reserved - Made
                    with <span
                        id="heart">&#x2764;</span></p>
            </div>
        </div>
    </footer>

</div>
