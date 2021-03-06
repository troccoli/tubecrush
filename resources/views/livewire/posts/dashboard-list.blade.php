<div class="md:col-span-3 py-5 md:py-0">
    <div class="flex flex-col space-y-3">
        <!-- Title (with create button) -->
        <div class="flex flex-auto justify-between items-center">
            <h2 class="text-2xl sm:text-3xl">List of all posts</h2>
            @can('create posts')
                <a href="{{ route('posts.create') }}" title="Create post" dusk="create-post-button"
                   class="w-1/5 md:w-auto p-1 flex justify-around rounded-md text-indigo-100 bg-indigo-600 hover:text-indigo-300 hover:bg-indigo-700">
                    <x-heroicons-o-document-add class="h-10 w-10" />
                </a>
            @endcan
        </div>
        <!-- list of posts -->
        <div>
            <div class="hidden md:flex flex-row bg-gray-100 rounded-t-lg h-10 items-center">
                <p class="w-1/2 pl-4 text-left text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    title</p>
                <p class="w-1/4 pl-2 text-left text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    date</p>
            </div>

            <div class="relative border-t border-t-gray-200">
                <div wire:loading.delay class="w-full h-full absolute block top-0 left-0 bg-white opacity-75 z-50"
                     dusk="loading-icon">
                    <div class="flex h-full items-center justify-center">
                        <x-loading-icon class=" h-20 w-20 text-gray-900"/>
                    </div>
                </div>
                <div dusk="posts-list" class="divide-y divide-gray-200">
                @foreach($posts as $post)
                    <!-- single post row -->
                        <div dusk="post"
                             class="@if($post->getId() === session('new-post-id')) animate-pulse-bg-once bg-green-50 @endif hover:bg-gray-100 py-4 flex flex-col md:flex-row">
                            <div class="w-full md:w-1/2 md:pl-4 text-lg text-gray-900">
                                {{ $post->getTitle() }}
                                <div class="mt-1 text-sm text-gray-500">by <span
                                        class="italic">{{ $post->author->getName() }}</span>
                                    <span class="md:hidden"> on <span
                                            class="font-semibold">{{ $post->getPublishedDate()->toFormattedDateString() }}</span></span>
                                </div>
                            </div>
                            <div class="hidden md:flex w-1/3 pl-2 text-sm">
                                {{ $post->getPublishedDate()->toFormattedDateString() }}
                            </div>
                            <div class="flex flex-row items-center mt-4 md:mt-0">
                                @can('update posts')
                                    <a href="{{ route('posts.update', ['postId' => $post->getId()]) }}"
                                       title="Edit post"
                                       class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 mr-1 border border-transparent rounded-md bg-gray-400 hover:bg-gray-500 transition duration-300"
                                       dusk="edit-post-button">
                                        <x-heroicons-o-pencil class="h-8 w-8" />
                                    </a>
                                @endcan
                                @can('delete posts')
                                    <a href="#" wire:click="confirmDelete({{ $post->getId() }})"
                                       title="Delete post"
                                       class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 ml-1 border border-transparent rounded-md bg-red-400 hover:bg-red-500 transition duration-300"
                                       dusk="delete-post-button">
                                        <x-heroicons-o-trash class="h-8 w-8" />
                                    </a>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Pagination -->
        {{--        <div class="flex justify-center md:hidden">--}}
        {{--            <button--}}
        {{--                class="w-full px-6 py-3 border rounded text-indigo-600 border-indigo-300 hover:bg-indigo-100 transition duration-300">--}}
        {{--                More posts--}}
        {{--            </button>--}}
        {{--        </div>--}}
        {{ $posts->links() }}
    </div>
    <x-jet-confirmation-modal wire:model="confirmingId" id="confirm-delete-post-dialog">
        <x-slot name="title">
            <p class="text-2xl font-bold tracking-wide">Delete a post</p>
        </x-slot>
        <x-slot name="content">
            <div class="mt-3">
                <p>{{ __('Are you sure you want to delete the following post?') }}</p>
                <p class="italic">{{ $confirmingTitle }}</p>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="keepPost" wire:loading.attr="disabled" dusk="cancel-delete-post-button">
                {{ __("Never mind") }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deletePost" wire:loading.attr="disabled" dusk="confirm-delete-post-button">
                {{ __('Yes please') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

</div>
