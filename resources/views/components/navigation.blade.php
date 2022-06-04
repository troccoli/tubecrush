<nav dusk="main-nav" data-cy="main-nav"
     x-data="{ openMenu: false, openProfileMenu: false, openLinesMenu: false }"
     class="bg-white sticky top-0 z-50">
    <!-- Primary Navigation Menu -->
    <div class="grid grid-cols-3 justify-between mx-2 py-3 max-w-7xl md:mx-6 lg:mx-10 xl:mx-auto">
        {{-- MENU --}}
        <div class="justify-self-start self-center">
            <div class="grid grid-flow-col space-x-8">
                {{-- Logo --}}
                <x-jet-application-mark class="block h-9 w-auto"/>

                {{-- Home --}}
                <div class="hidden md:flex">
                    <x-jet-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
                        {{ __('Home') }}
                    </x-jet-nav-link>
                </div>

                {{-- Line --}}
                <x-jet-nav-dropdown dusk="lines-dropdown-menu" data-cy="lines-dropdown-menu"
                                    :active="request()->routeIs('posts-by-lines')">
                    <x-slot name="name">{{ __('Lines') }}</x-slot>
                    @foreach(\App\Models\Line::query()->orderBy('name')->get() as $line)
                        <x-jet-dropdown-link dusk="{{ $line->getSlug() }}-link" data-cy="{{ $line->getSlug() }}-link"
                                             href="{{ route('posts-by-lines', ['slug' => $line->getSlug()]) }}">
                            {{ $line->getName() }}
                        </x-jet-dropdown-link>
                    @endforeach
                </x-jet-nav-dropdown>
            </div>
        </div>
        {{-- SEND CRUSH --}}
        <div class="justify-self-center self-center">
            <button type="button" dusk="send-crush-button" data-cy="send-crush-button"
                    class="w-32 px-6 py-1 rounded-lg bg-blue-300 hover:bg-blue-500 transition"
                    @click.stop='window.location.href="{{ route('send-crush') }}"'>
                <x-heroicons-s-camera class="h-6 w-6 mx-auto my-1"/>
            </button>
        </div>
        {{-- PROFILE --}}
        <div class="justify-self-end self-center">
            @if (auth()->check())
                <div class="hidden md:flex md:items-center md:ml-6">
                    <x-jet-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition">
                                <div>{{ auth()->user()->getName() }}</div>
                                <div class="ml-1">
                                    <x-heroicons-s-chevron-down class="fill-current h-4 w-4"/>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div dusk="dropdown-menu" data-cy="dropdown-menu">
                                @can('create posts')
                                    <x-jet-dropdown-link
                                        href="{{ route('posts.create') }}">{{ __('Create post') }}</x-jet-dropdown-link>
                                    <div class="border-t border-gray-100"></div>
                                @endcan
                                <x-jet-dropdown-link
                                    href="{{ route('dashboard') }}">{{ __('Dashboard') }}</x-jet-dropdown-link>
                                <x-jet-dropdown-link
                                    href="{{ route('profile.show') }}">{{ __('Profile') }}</x-jet-dropdown-link>
                                <div class="border-t border-gray-100"></div>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-jet-dropdown-link href="{{ route('logout') }}"
                                                         onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log out') }}</x-jet-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
            @endif
            <!-- Hamburger -->
            <div class="flex items-center md:hidden">
                <button @click.stop="openMenu = ! openMenu"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition duration-300">
                    <span :class="{'hidden': openMenu, 'inline-flex': ! openMenu }"><x-heroicons-o-bars-3
                            class="h-6 w-6"/></span>
                    <span :class="{'hidden': ! openMenu, 'inline-flex': openMenu }" class="hidden"><x-heroicons-o-x-mark
                            class="h-6 w-6"/></span>
                </button>
            </div>
        </div>
    </div>
    <div :class="{'block': openMenu, 'hidden': ! openMenu}" class="hidden">
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="mt-3 space-y-1">
                {{-- Home --}}
                <x-jet-responsive-nav-link href="{{ route('home') }}"
                                           :active="request()->routeIs('home')">{{ __('Home') }}</x-jet-responsive-nav-link>
                {{-- Line --}}
                <div>
                    <button @click.stop="openLinesMenu = ! openLinesMenu"
                            class="flex justify-between w-full py-2 text-base font-medium text-gray-600">
                        <div class="ml-4 text-left">{{ __('Lines') }}</div>
                        <div class="mr-5 flex flex-wrap content-center">
                            <div :class="{'block': ! openLinesMenu, 'hidden': openLinesMenu}">
                                <x-heroicons-s-chevron-down class="fill-current h-4 w-4"/>
                            </div>
                            <div :class="{'block': openLinesMenu, 'hidden': ! openLinesMenu}">
                                <x-heroicons-s-chevron-up class="fill-current h-4 w-4"/>
                            </div>
                        </div>
                    </button>
                </div>
                <div :class="{'block': openLinesMenu, 'hidden': ! openLinesMenu}" class="hidden sm:hidden">
                    @foreach(\App\Models\Line::query()->orderBy('name')->get() as $line)
                        <x-jet-responsive-nav-link
                            href="{{ route('posts-by-lines', ['slug' => $line->getSlug()]) }}">{{ $line->getName() }}</x-jet-responsive-nav-link>
                    @endforeach</div>
                {{-- PROFILE --}}
                @if (auth()->check())
                    <div class="border-t border-gray-100">
                        <button @click.stop="openProfileMenu = ! openProfileMenu"
                                class="flex justify-between w-full py-2 text-base font-medium text-gray-600">
                            <div class="ml-4 text-left">{{ auth()->user()->getName() }}</div>
                            <div class="mr-5 flex flex-wrap content-center">
                                <div :class="{'block': ! openProfileMenu, 'hidden': openProfileMenu}">
                                    <x-heroicons-s-chevron-down class="fill-current h-4 w-4"/>
                                </div>
                                <div :class="{'block': openProfileMenu, 'hidden': ! openProfileMenu}">
                                    <x-heroicons-s-chevron-up class="fill-current h-4 w-4"/>
                                </div>
                            </div>
                        </button>
                    </div>
                    <div :class="{'block': openProfileMenu, 'hidden': ! openProfileMenu}" class="hidden sm:hidden">
                        @can('create posts')
                            <div class="border-t border-gray-100">
                                <x-jet-responsive-nav-link href="{{ route('posts.create') }}"
                                                           :active="request()->routeIs('posts.create')">{{ __('Create post') }}</x-jet-responsive-nav-link>
                            </div>
                        @endcan
                        <div class="border-t border-gray-100">
                            <x-jet-responsive-nav-link href="{{ route('dashboard') }}"
                                                       :active="request()->routeIs('dashboard')">{{ __('Dashboard') }}</x-jet-responsive-nav-link>
                            <x-jet-responsive-nav-link href="{{ route('profile.show') }}"
                                                       :active="request()->routeIs('profile.show')">{{ __('Profile') }}</x-jet-responsive-nav-link>
                        </div>
                        <div class="border-t border-gray-100">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-jet-responsive-nav-link href="{{ route('logout') }}"
                                                           onclick="event.preventDefault(); this.closest('form').submit();">{{ __('Log out') }}</x-jet-responsive-nav-link>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</nav>
