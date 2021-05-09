<div x-data="{ userCanVote: @entangle('userCanVote'), userHasVoted: @entangle('userHasVoted') }"
     :key="{{ $post->getId() }}"
     class="max-w-2xl mx-auto my-6 bg-white overflow-hidden shadow-md rounded-lg flex flex-col"
     dusk="post">
    <img class="object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($post->getPhoto()) }}"
         alt="Cover photo" dusk="photo">

    <div class="flex justify-between px-6 pt-2 text-sm">
        <div class="flex" dusk="likes">
            <button :disabled="!userCanVote" dusk="likes-button"
                    class="disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none"
                    @click="if (userHasVoted) { $wire.unvote() } else { $wire.vote() }"
            >
                <x-heroicons-o-fire class="h-5 w-5 inline-block mr-1" dusk="likes-icon-not-voted"
                                    x-show="!userHasVoted"/>
                <x-heroicons-s-fire class="h-5 w-5 inline-block mr-1 text-red-600" dusk="likes-icon-voted"
                                    x-show="userCanVote && userHasVoted"/>
            </button>
            <p class="my-auto">{{ trans_choice('post.likes', $post->getLikes()) }}</p>
        </div>
        @if($post->getPhotoCredit())
            <div dusk="photo-credit">
                Photo by: {{ $post->getPhotoCredit() }}
            </div>
        @endif

    </div>
    <div class="p-6">
        <div class="mb-6 h-8 cursor-pointer flex flex-auto" dusk="line"
             @click="window.location.href='{{ route('posts-by-lines', ['slug' => $post->line->getSlug()]) }}'">
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
                <div dusk="tag-{{ $tag->getSlug() }}" class="cursor-pointer"
                     @click="window.location.href='{{ route('posts-by-tags', ['slug' => $tag->getSlug()]) }}'">
                    <x-tag>{{ $tag->getName() }}</x-tag>
                </div>
            @endforeach
        </div>
    </div>
</div>
