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
                               class="static md:w-3/4 flex justify-center py-2 px-4 border border-transparent rounded-md text-indigo-100 bg-indigo-600 hover:bg-indigo-700 transition duration-300">
                                <span class="static mr-4">
                                    <x-heroicons-o-users class="h-5 w-5 text-indigo-100"/>
                                </span>
                                Users
                            </a>
                        @endcan
                        @can('view posts')
                            <a href="{{ route('posts.list') }}"
                               class="static md:w-3/4 flex justify-center py-2 px-4 border border-transparent rounded-md text-indigo-100 bg-indigo-600 hover:bg-indigo-700 transition duration-300">
                                <span class="static mr-4">
                                    <x-heroicons-o-newspaper class="h-5 w-5 text-indigo-100"/>
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
