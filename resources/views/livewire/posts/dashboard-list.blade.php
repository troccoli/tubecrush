<div class="md:col-span-3 py-5 md:py-0">
    <div class="flex flex-col space-y-3">
        <!-- Title (with create button) -->
        <div class="flex flex-auto justify-between items-center">
            <h2 class="text-2xl sm:text-3xl">List of all posts</h2>
            @can('create posts')
                <a href="#" title="Create post"
                   class="w-1/5 md:w-auto p-1 flex justify-around rounded-md text-indigo-100 bg-indigo-600 hover:text-indigo-300 hover:bg-indigo-700">
                    <!-- Heroicon name: document-add -->
                    <svg class="h-10 w-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
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

            <div class="divide-y divide-gray-200 border-t border-t-gray-200" dusk="posts-list">
            @foreach($posts as $post)
                <!-- single post row -->
                    <div class="hover:bg-gray-100 py-4 flex flex-col md:flex-row" dusk="post">
                        <div class="w-full md:w-1/2 md:pl-4 text-lg text-gray-900">
                            {{ $post->getTitle() }}
                            <div class="mt-1 text-sm text-gray-500">by <span
                                    class="italic">{{ $post->author->getName() }}</span>
                                <span class="md:hidden"> on <span
                                        class="font-semibold">{{ $post->getPublishedDate()->toFormattedDateString() }}</span></span>
                            </div>
                        </div>
                        <div
                            class="hidden md:flex w-1/3 pl-2 text-sm">{{ $post->getPublishedDate()->toFormattedDateString() }}</div>
                        <div class="flex flex-row items-center mt-4 md:mt-0">
                            @can('update posts')
                                <a href="#" title="Edit post"
                                   class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 mr-1 border border-transparent rounded-md bg-gray-400 hover:bg-gray-500 transition duration-300"
                                   dusk="edit-post">
                                    <!-- Heroicon name: pencil -->
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            @endcan
                            @can('delete posts')
                                <a href="#" title="Delete post"
                                   class="flex justify-around w-1/2 md:w-auto md:px-1 py-1 ml-1 border border-transparent rounded-md bg-red-400 hover:bg-red-500 transition duration-300"
                                   dusk="delete-post">
                                    <!-- Heroicon name: trash -->
                                    <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none"
                                         viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </a>
                            @endcan
                        </div>
                    </div>
                @endforeach
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
</div>
