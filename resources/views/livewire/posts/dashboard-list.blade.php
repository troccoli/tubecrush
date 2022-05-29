<div class="md:col-span-3 py-5 md:py-0">
    <div class="flex flex-col space-y-3">
        <!-- Title (with create button) -->
        <div class="flex flex-auto justify-between items-center">
            <h2 class="text-2xl sm:text-3xl">List of all posts</h2>
            @can('create posts')
                <a href="{{ route('posts.create') }}" title="Create post" dusk="create-post-button"
                   cy="create-post-button"
                   class="w-1/5 md:w-auto p-1 flex justify-around rounded-md text-indigo-100 bg-indigo-600 hover:text-indigo-300 hover:bg-indigo-700">
                    <x-heroicons-o-document-add class="h-10 w-10"/>
                </a>
            @endcan
        </div>
        <!-- list of posts -->
        <div>
            <div class="hidden md:flex flex-row bg-gray-100 rounded-t-lg h-10 items-center">
                <p class="w-1/2 pl-4 text-left text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    title</p>
                <p class="w-1/6 pl-2 text-left text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    date</p>
                <p class="w-1/6 pl-2 text-left text-xs lg:text-sm font-medium text-gray-500 uppercase tracking-wider">
                    status</p>
            </div>

            <div class="relative border-t border-t-gray-200">
                <div wire:loading.delay class="w-full h-full absolute block top-0 left-0 bg-white opacity-75 z-50"
                     dusk="loading-icon" cy="loading-icon">
                    <div class="flex h-full items-center justify-center">
                        <x-loading-icon class=" h-20 w-20 text-gray-900"/>
                    </div>
                </div>
                <div dusk="posts-list" cy="posts-list" class="divide-y divide-gray-200">
                    @foreach($posts as $post)
                        <!-- single post row -->
                        <div dusk="post" cy="post"
                             class="@if($post->getKey() === session('new-post-id')) animate-pulse-bg-once bg-green-50 @endif hover:bg-gray-100 py-4 flex flex-col md:flex-row">
                            <div class="w-full md:w-1/2 md:pl-4 text-lg text-gray-900">
                                <span dusk="post-title" cy="post-title">{{ $post->getTitle() }}</span>
                                <div class="mt-1 text-sm text-gray-500">
                                    by
                                    <span class="italic" dusk="post-author" cy="post-author">
                                        {{ $post->author->getName() }}
                                    </span>
                                    <span class="md:hidden">
                                        on
                                        <span class="font-semibold">
                                            {{ $post->getCreationDate()->toFormattedDateString() }}
                                        </span>
                                    </span>
                                </div>
                                <div class="mt-1 text-sm text-gray-500 md:hidden" dusk="post-status" cy="post-status">
                                    @if($post->isDraft())
                                        Draft
                                    @else
                                        Published on
                                        <span class="font-semibold">
                                        {{ $post->getPublishedDate()->toFormattedDateString() }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="hidden md:flex w-1/6 pl-2 text-sm" dusk="post-creation-date"
                                 cy="post-creation-date">
                                {{ $post->getCreationDate()->toFormattedDateString() }}
                            </div>
                            <div class="hidden md:flex w-1/6 pl-2 text-sm" dusk="post-publication-date"
                                 cy="post-publication-date">
                                @if($post->isDraft())
                                    Draft
                                @else
                                    {{ $post->getPublishedDate()->toFormattedDateString() }}
                                @endif
                            </div>
                            <div class="flex flex-row items-center mt-4 md:mt-0">
                                @can('update posts')
                                    <a href="{{ route('posts.update', ['postId' => $post->getKey()]) }}"
                                       title="Edit post"
                                       class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 mr-1 border border-transparent rounded-md bg-gray-400 hover:bg-gray-500 transition duration-300"
                                       dusk="edit-post-button" cy="edit-post-button">
                                        <x-heroicons-o-pencil class="h-8 w-8"/>
                                    </a>
                                @endcan
                                @can('delete posts')
                                    <a href="#" wire:click="confirmAction({{ $post->getKey() }}, 'delete')"
                                       title="Delete post"
                                       class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 ml-1 border border-transparent rounded-md bg-red-400 hover:bg-red-500 transition duration-300"
                                       dusk="delete-post-button" cy="delete-post-button">
                                        <x-heroicons-o-trash class="h-8 w-8"/>
                                    </a>
                                @endcan
                                @can('publish posts')
                                    @if($post->isDraft())
                                        <a href="#" wire:click="confirmAction({{ $post->getKey() }}, 'publish')"
                                           title="Publish post"
                                           class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 ml-1 border border-transparent rounded-md bg-emerald-400 hover:bg-emerald-500 transition duration-300"
                                           dusk="publish-post-button" cy="publish-post-button">
                                            <x-heroicons-o-eye class="h-8 w-8"/>
                                        </a>
                                    @else
                                        <a href="#" wire:click="confirmAction({{ $post->getKey() }}, 'unpublish')"
                                           title="Unpublish post"
                                           class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 ml-1 border border-transparent rounded-md bg-cyan-400 hover:bg-cyan-500 transition duration-300"
                                           dusk="unpublish-post-button" cy="unpublish-post-button">
                                            <x-heroicons-o-eye-off class="h-8 w-8"/>
                                        </a>
                                    @endif
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
    <x-jet-confirmation-modal wire:model="confirmingDeletingId" id="confirm-delete-post-dialog">
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
            <x-jet-secondary-button wire:click="keepPost" wire:loading.attr="disabled" dusk="cancel-delete-post-button"
                                    cy="cancel-delete-post-button">
                {{ __("Never mind") }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="deletePost" wire:loading.attr="disabled"
                                 dusk="confirm-delete-post-button" cy="confirm-delete-post-button">
                {{ __('Yes please') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    <x-jet-confirmation-modal wire:model="confirmingPublishingId" id="confirm-publish-post-dialog">
        <x-slot name="title">
            <p class="text-2xl font-bold tracking-wide">Publish a post</p>
        </x-slot>
        <x-slot name="content">
            <div class="mt-3">
                <p>{{ __('Are you sure you want to publish the following post?') }}</p>
                <p class="italic">{{ $confirmingTitle }}</p>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="keepPost" wire:loading.attr="disabled"
                                    dusk="cancel-publish-post-button" cy="cancel-publish-post-button">
                {{ __("Whoops, no thanks") }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="publishPost" wire:loading.attr="disabled"
                                 dusk="confirm-publish-post-button" cy="confirm-publish-post-button">
                {{ __('Yep, let\'s go') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
    <x-jet-confirmation-modal wire:model="confirmingUnpublishingId" id="confirm-unpublish-post-dialog">
        <x-slot name="title">
            <p class="text-2xl font-bold tracking-wide">Unpublish a post</p>
        </x-slot>
        <x-slot name="content">
            <div class="mt-3">
                <p>{{ __('Are you sure you want to unpublish the following post?') }}</p>
                <p class="italic">{{ $confirmingTitle }}</p>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-jet-secondary-button wire:click="keepPost" wire:loading.attr="disabled"
                                    dusk="cancel-unpublish-post-button" cy="cancel-unpublish-post-button">
                {{ __("Naah, leave it") }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-2" wire:click="unpublishPost" wire:loading.attr="disabled"
                                 dusk="confirm-unpublish-post-button" cy="confirm-unpublish-post-button">
                {{ __('Oh yeah') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>
