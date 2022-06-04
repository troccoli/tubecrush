<div x-data="{ userCanVote: @entangle('userCanVote'), userHasVoted: @entangle('userHasVoted') }"
     :key="{{ $post->getKey() }}"
     class="max-w-2xl mx-auto my-6 bg-white overflow-hidden shadow-md rounded-lg flex flex-col"
     dusk="post" data-cy="post">
    <img class="object-cover" src="{{ \Illuminate\Support\Facades\Storage::url($post->getPhoto()) }}"
         alt="Cover photo" dusk="photo" data-cy="photo">

    <div class="flex justify-between px-6 pt-2 text-sm">
        <div class="flex" dusk="likes" data-cy="likes">
            <button :disabled="!userCanVote" dusk="likes-button" data-cy="likes-button"
                    class="disabled:opacity-50 disabled:cursor-not-allowed focus:outline-none"
                    @click.stop="if (userHasVoted) { $wire.unvote() } else { $wire.vote() }"
            >
                <x-heroicons-o-fire class="h-5 w-5 inline-block mr-1" dusk="likes-icon-not-voted"
                                    data-cy="likes-icon-not-voted"
                                    x-show="!userHasVoted"/>
                <x-heroicons-s-fire class="h-5 w-5 inline-block mr-1 text-red-600" dusk="likes-icon-voted"
                                    data-cy="likes-icon-voted"
                                    x-show="userCanVote && userHasVoted"/>
            </button>
            <p class="my-auto">{{ trans_choice('post.likes', $post->getLikes()) }}</p>
        </div>
        @if($post->getPhotoCredit())
            <div dusk="photo-credit" data-cy="photo-credit">
                Photo by: {{ $post->getPhotoCredit() }}
            </div>
        @endif

    </div>
    <div class="p-6">
        <div class="mb-6 h-8 cursor-pointer flex flex-auto" dusk="line" data-cy="line"
             @click.stop="window.location.href='{{ route('posts-by-lines', ['slug' => $post->line->getSlug()]) }}'">
            <x-line-box>
                @slot('class')
                    {{ $post->line->getSlug() }}
                @endslot
                {{ $post->line->getName() }}
            </x-line-box>
        </div>
        <div>
            <a href="{{ route('single-post', ['post' => $post]) }}"
               class="cursor-pointer block text-gray-800 font-semibold text-2xl mt-2"
               dusk="title" data-cy="title">{{ $post->getTitle() }}</a>
            <p class="mt-2 text-gray-600 text-xs font-normal"
               dusk="published-date"
               data-cy="published-date">{{ $post->getPublishedDate()->toFormattedDateString() }}</p>
            <p class="text-sm text-gray-600 mt-2" dusk="content" data-cy="content">{{ $post->getContent() }}</p>
        </div>

        <div class="mt-4 flex flex-wrap justify-start" dusk="tags" data-cy="tags">
            @foreach($post->tags as $tag)
                <div dusk="tag-{{ $tag->getSlug() }}" data-cy="tag-{{ $tag->getSlug() }}" class="cursor-pointer"
                     @click.stop="window.location.href='{{ route('posts-by-tags', ['slug' => $tag->getSlug()]) }}'">
                    <x-tag>{{ $tag->getName() }}</x-tag>
                </div>
            @endforeach
        </div>
        <div class="mt-4 flex justify-between">
            <div class="flex space-x-2" dusk="shares" data-cy="shares">
                <x-twitter-share :post="$post"/>
                <x-facebook-share :post="$post"/>
                <x-copy-link-share :post="$post"/>
            </div>
            @unless($withComments || !config('disqus.enabled'))
                <div wire:ignore class="text-sm" dusk="comments-count" data-cy="comments-count">
                    <span class="disqus-comment-count" data-disqus-url="{{ route('single-post', ['post' => $post]) }}">0 Comments</span>
                </div>
            @endunless
        </div>
        @if(config('disqus.enabled') && $withComments)
            <div wire:ignore dusk="comments" data-cy="comments">
                <div id="disqus_thread"></div>
            </div>
        @endif
    </div>
</div>
