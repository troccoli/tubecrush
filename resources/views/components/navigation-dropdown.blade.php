<nav x-data="{ openMenu: false, openProfileMenu: false, openLinesMenu: false }"
     class="bg-white border-b border-gray-100 sticky top-0" dusk="main-nav">
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
                <div class="hidden sm:flex sm:items-center sm:ml-6">
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
                            <div dusk="dropdown-menu">
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
                            </div>
                        </x-slot>
                    </x-jet-dropdown>
                </div>
        @endif

        <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="openMenu = ! openMenu"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <div :class="{'hidden': openMenu, 'inline-flex': ! openMenu }">
                        <x-heroicon-o-menu class="h-6 w-6"/>
                    </div>
                    <div :class="{'hidden': ! openMenu, 'inline-flex': openMenu }">
                        <x-heroicons-o-x class="h-6 w-6"/>
                    </div>
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
