<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(":name's Dashboard", ['name' => auth()->user()->name]) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden">
                <div class="md:grid md:grid-flow-col md:grid-cols-4">
                    <div class="space-y-2 text-base font-medium">
                        @can('register user')
                            <a href="{{ route('register') }}"
                               class="relative md:w-3/4 flex justify-center py-2 px-4 border border-transparent rounded-md text-indigo-100 bg-indigo-600 hover:bg-indigo-700 transition duration-300">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <!-- Heroicon name: users -->
                                    <svg class="h-5 w-5 text-indigo-100" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </span>
                                Users
                            </a>
                        @endcan
                        @can('view posts')
                            <a href="{{ route('posts') }}"
                               class="relative md:w-3/4 flex justify-center py-2 px-4 border border-transparent rounded-md text-indigo-100 bg-indigo-600 hover:bg-indigo-700 transition duration-300">
                                <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                                    <!-- Heroicon name: newspaper -->
                                    <svg class="h-5 w-5 text-indigo-100" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </span>
                                Posts
                            </a>
                        @endcan
                    </div>
                    <div class="md:col-span-3 py-5 md:py-0">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
