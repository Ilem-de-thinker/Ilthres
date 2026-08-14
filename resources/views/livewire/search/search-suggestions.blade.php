<div class="relative" wire:click.away="closeDropdown">
    <div class="relative">
        <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <input
            wire:model.live.debounce.300ms="search"
            wire:focus="refreshSuggestions"
            type="search"
            placeholder="Search businesses, products, services..."
            class="block w-full rounded-full border border-gray-300 bg-white py-3 pl-12 pr-4 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
            autocomplete="off"
        >
    </div>

    @if ($showDropdown && count($suggestions) > 0)
        <div class="absolute z-50 mt-2 w-full rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="p-2">
                @if (strlen($this->search) < config('search.suggestion_min_chars', 2))
                    <p class="px-3 py-1 text-[10px] font-medium uppercase tracking-wider text-gray-400">Popular</p>
                @else
                    <p class="px-3 py-1 text-[10px] font-medium uppercase tracking-wider text-gray-400">Suggestions</p>
                @endif

                @foreach ($suggestions as $suggestion)
                    <button
                        wire:click="selectSuggestion('{{ addslashes($suggestion['text']) }}')"
                        type="button"
                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left hover:bg-gray-50 transition"
                    >
                        @if ($suggestion['image'])
                            <img src="{{ Storage::url($suggestion['image']) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                        @else
                            <div class="flex h-8 w-8 items-center justify-center rounded-full
                                @if ($suggestion['type'] === 'business') bg-gray-100 text-gray-500
                                @elseif ($suggestion['type'] === 'product') bg-blue-100 text-blue-600
                                @else bg-purple-100 text-purple-600 @endif
                                text-xs font-semibold">
                                {{ strtoupper(substr($suggestion['text'], 0, 2)) }}
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $suggestion['text'] }}</p>
                            @if ($suggestion['subtitle'])
                                <p class="text-xs text-gray-500 truncate">{{ $suggestion['subtitle'] }}</p>
                            @endif
                        </div>

                        <span class="shrink-0 rounded-full px-2 py-0.5 text-[10px] font-medium
                            @if ($suggestion['type'] === 'business') bg-gray-100 text-gray-600
                            @elseif ($suggestion['type'] === 'product') bg-blue-100 text-blue-700
                            @else bg-purple-100 text-purple-700 @endif">
                            {{ ucfirst($suggestion['type']) }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    @endif
</div>
