<div class="flex flex-col">
    @foreach($posts as $post)
        <livewire:posts.single-post :post="$post" :wire:key="$post->getKey()" :withComments="false"/>
    @endforeach
    <div class="flex">
        <button wire:click="loadMorePosts" wire:loading.attr="disabled" data-cy="more-posts-button"
                class="max-w-2xl mx-auto w-full px-6 py-3 border-2 rounded-lg text-gray-600 border-gray-300 bg-gray-100 hover:bg-gray-300 disabled:bg-gray-300 transition">
            <x-loading-icon/>
            <span>More posts</span>
        </button>
    </div>
</div>
@push('scripts')
    <script id="dsq-count-scr" src="//{{ config('disqus.username') }}.disqus.com/count.js" async></script>
    <script type="application/javascript">
        Livewire.on('morePostsLoaded', () => {
            DISQUSWIDGETS.getCount({reset: true});
        })
    </script>
@endpush
