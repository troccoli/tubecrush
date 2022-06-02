<div dusk="copy-link-share" cy="copy-link-share" x-data="{ shown: false, timeout: null }" class="flex">
    <a dusk="copy-link-share-link" cy="copy-link-share-link"
       @click.stop.prevent="navigator.clipboard.writeText('{{ $postUrl() }}'); $dispatch('copied-{{ $uniqueId() }}')"
       title="Copy URL"
       class="cursor-pointer">
        <x-heroicons-o-link class="w-5 h-5"/>
    </a>
    <div x-show="shown" x-transition.opacity.out.duration.1000ms dusk="copy-link-share-alert" cy="copy-link-share-alert"
         class="ml-1 px-1 bg-green-50 rounded-lg text-green-900 shadow-md" role="alert"
         x-on:copied-{{ $uniqueId() }}.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);">
        <div class="flex">
            <x-heroicons-s-check-circle class="h-5 w-5 mr-1 d-inline" fill="green"/>
            <p class="text-xs pt-0.5">The URL has been copied</p>
        </div>
    </div>
</div>
