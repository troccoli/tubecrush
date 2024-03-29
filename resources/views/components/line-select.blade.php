<div class="static mt-2" x-data="{ open: false }" @click.outside="open = false" @close.stop="open = false"
     dusk="line-select" cy="line-select">
    <div @click.stop="open = ! open">
        <button type="button" aria-haspopup="listbox" aria-expanded="true" aria-labelledby="listbox-label"
                class="relative w-full bg-white border border-gray-500 rounded-md shadow-sm pl-3 pr-10 py-3 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            <div class="flex items-center text-base">
                @if($this->line)
                    @foreach(\App\Models\Line::all() as $line)
                        <div class="flex items-center" x-show="$wire.line == {{ $line->getKey() }}">
                            <div class="absolute w-1.5 h-full {{ $line->getSlug() }}"></div>
                            <span class="ml-3 block truncate">{{ $line->getName() }}</span>
                        </div>
                    @endforeach
                @else
                    Choose one line
                @endif
            </div>
            <span class="ml-3 absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <x-heroicons-s-chevron-up-down class="h-5 w-5 text-gray-400"/>
            </span>
        </button>
    </div>

    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="z-40 w-full mt-1 rounded-md shadow-lg"
         style="display: none;"
         @click.stop="open = false">
        <div class="w-full bg-white">
            <ul tabindex="-1" role="listbox" aria-labelledby="listbox-label"
                aria-activedescendant="listbox-item-3"
                class="max-h-56 rounded-md text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm">
                @foreach(\App\Models\Line::all() as $line)
                    <li id="line-{{ $line->getKey() }}" role="option" dusk="{{ $line->getSlug() }}-option"
                        cy="{{ $line->getSlug() }}-option"
                        class="text-base text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-gray-100"
                        @click.stop="$wire.set('line', {{ $line->getKey() }}); open = false">
                        <div class="flex items-center">
                            <div class="absolute w-1.5 h-full {{ $line->getSlug()}}"></div>
                            <span class="ml-3 block truncate">{{ $line->getName() }}</span>
                        </div>
                        @if($this->line == $line->getKey())
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4">
                                <x-heroicons-s-check class="h-5 w-5"/>
                            </span>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
