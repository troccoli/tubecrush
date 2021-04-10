<div class="flex flex-col">
    @foreach($posts as $post)
        <div  x-data x-bind:key="{{ $post->getId() }}"
            class="max-w-2xl mx-auto my-6 bg-white overflow-hidden shadow-md rounded-lg flex flex-col"
            dusk="post">
            <img class="object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($post->getPhoto()) }}"
                 alt="Cover photo" dusk="photo">

            @if($post->getPhotoCredit())
                <div class="pl-6 pt-2 text-sm" dusk="photo-credit">
                    Photo by: {{ $post->getPhotoCredit() }}
                </div>
            @endif

            <div class="p-6">
                <div class="mb-6 h-8 cursor-pointer flex flex-auto" dusk="line" @click="window.location.href='{{ route('posts-by-lines', ['slug' => $post->line->getSlug()]) }}'">
                    <x-line-box>
                        @slot('class'){{ $post->line->getSlug() }}@endslot
                        {{ $post->line->getName() }}
                    </x-line-box>
                </div>
                <div>
                    <p class="block text-gray-800 font-semibold text-2xl mt-2" dusk="title">{{ $post->getTitle() }}</p>
                    <p class="text-sm text-gray-600 mt-2" dusk="content">{{ $post->getContent() }}</p>
                </div>

                <div class="mt-4" dusk="author-with-date">
                    <div class="flex items-center">
                        <p class="text-gray-700 font-semibold">{{ $post->author->getName() }} <span
                                class="mx-1 text-gray-600 text-xs font-normal">{{ $post->getPublishedDate()->toFormattedDateString() }}</span>
                        </p>
                    </div>
                </div>
                <div class="mt-4 flex flex-wrap justify-start" dusk="tags">
                @foreach($post->tags as $tag)
                    <x-tag>{{ $tag->getName() }}</x-tag>
                @endforeach
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
