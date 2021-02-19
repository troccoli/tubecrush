<div class="flex flex-col">
    @foreach($posts as $post)
        <div
            class="max-w-2xl mx-auto my-6 bg-white overflow-hidden shadow-md rounded-lg flex flex-col"
            dusk="post">
            <img class="object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($post->getPhoto()) }}"
                 alt="Article">

            <div class="p-6">
                <div>
                    <span class="text-blue-600 text-xs font-medium uppercase">Circle Line</span>
                    <p class="block text-gray-800 font-semibold text-2xl mt-2">{{ $post->getTitle() }}</p>
                    <p class="text-sm text-gray-600 mt-2">{{ $post->getContent() }}</p>
                </div>

                <div class="mt-4">
                    <div class="flex items-center">
                        <p class="text-gray-700 font-semibold">{{ $post->author->getName() }} <span
                                class="mx-1 text-gray-600 text-xs font-normal">{{ $post->getPublishedDate()->toFormattedDateString() }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <div class="flex">
        <button wire:click="loadMorePosts" wire:loading.attr="disabled"
                class="max-w-2xl mx-auto w-full px-6 py-3 border-2 rounded-lg text-gray-600 border-gray-300 bg-gray-100 hover:bg-gray-300 disabled:bg-gray-300 transition">
            <x-loading-icon/>
            <span>More posts</span>
        </button>
    </div>
</div>
