<nav x-data="{ openMenu: false, openProfileMenu: false, openLinesMenu: false }"
     class="bg-white border-b border-gray-100" dusk="main-nav">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <x-jet-application-mark class="block h-9 w-auto"/>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-jet-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-jet-nav-link>
                </div>
                <x-jet-nav-dropdown dusk="lines-dropdown-menu" :active="request()->routeIs('posts-by-lines')">
                    <x-slot name="name">{{ __('Lines') }}</x-slot>
                    @foreach(\App\Models\Line::query()->orderBy('name')->get() as $line)
                        <x-jet-dropdown-link href="{{ route('posts-by-lines', ['slug' => $line->getSlug()]) }}"
                                             dusk="{{ $line->getSlug() }}-link">
                            {{ $line->getName() }}
                        </x-jet-dropdown-link>
                    @endforeach
                </x-jet-nav-dropdown>
            </div>

        @if (auth()->check())
            <!-- Settings Dropdown -->
                <div dusk="dropdown-menu" class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                                <div>{{ auth()->user()->getName() }}</div>

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
                            @can('create posts')
                                <x-jet-dropdown-link href="{{ route('posts.create') }}">
                                    {{ __('Create post') }}
                                </x-jet-dropdown-link>
                                <div class="border-t border-gray-100"></div>
                            @endcan
                            <x-jet-dropdown-link href="{{ route('dashboard') }}">
                                {{ __('Dashboard') }}
                            </x-jet-dropdown-link>
                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>

                            <div class="border-t border-gray-100"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-jet-dropdown-link href="{{ route('logout') }}"
                                                     onclick="event.preventDefault();
                                                            this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-jet-dropdown-link>
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
        @endif

        <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="openMenu = ! openMenu"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': openMenu, 'inline-flex': ! openMenu }" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! openMenu, 'inline-flex': openMenu }" class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': openMenu, 'hidden': ! openMenu}" class="hidden sm:hidden">
        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                <x-jet-responsive-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-jet-responsive-nav-link>
                <div>
                    <button @click="openLinesMenu = ! openLinesMenu"
                            class="flex justify-between w-full py-2 text-base font-medium text-gray-600">
                        <div class="ml-4 text-left">{{ __('Lines') }}</div>
                        <div class="mr-5 flex flex-wrap content-center">
                            <svg :class="{'block': ! openLinesMenu, 'hidden': openLinesMenu}"
                                 class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <svg :class="{'block': openLinesMenu, 'hidden': ! openLinesMenu}"
                                 class="fill-current h-4 w-4"
                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </button>
                </div>
                <div :class="{'block': openLinesMenu, 'hidden': ! openLinesMenu}" class="hidden sm:hidden">
                    @foreach(\App\Models\Line::query()->orderBy('name')->get() as $line)
                        <x-jet-responsive-nav-link href="{{ route('posts-by-lines', ['slug' => $line->getSlug()]) }}">
                            {{ $line->getName() }}
                        </x-jet-responsive-nav-link>
                    @endforeach</div>
                @if (auth()->check())
                    <div class="border-t border-gray-100">
                        <button @click="openProfileMenu = ! openProfileMenu"
                                class="flex justify-between w-full py-2 text-base font-medium text-gray-600">
                            <div class="ml-4 text-left">{{ auth()->user()->getName() }}</div>
                            <div class="mr-5 flex flex-wrap content-center">
                                <svg :class="{'block': ! openProfileMenu, 'hidden': openProfileMenu}"
                                     class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                     viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                                <svg :class="{'block': openProfileMenu, 'hidden': ! openProfileMenu}"
                                     class="fill-current h-4 w-4"
                                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                     fill="currentColor">
                                    <path fill-rule="evenodd"
                                          d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                    </div>
                    <div :class="{'block': openProfileMenu, 'hidden': ! openProfileMenu}" class="hidden sm:hidden">
                        @can('create posts')
                            <div class="border-t border-gray-100">
                                <x-jet-responsive-nav-link href="{{ route('posts.create') }}"
                                                           :active="request()->routeIs('posts.create')">
                                    {{ __('Create post') }}
                                </x-jet-responsive-nav-link>
                            </div>
                        @endcan
                        <div class="border-t border-gray-100">
                            <x-jet-responsive-nav-link href="{{ route('dashboard') }}"
                                                       :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-jet-responsive-nav-link>
                            <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                                       :active="request()->routeIs('profile.show')">
                                {{ __('Profile') }}
                            </x-jet-responsive-nav-link>
                        </div>
                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                                           onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    {{ __('Log out') }}
                                </x-jet-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
