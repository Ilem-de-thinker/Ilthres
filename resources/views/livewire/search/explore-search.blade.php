<div>
    {{-- Search --}}
    <div class="mx-auto max-w-xl">
        <livewire:search.search-suggestions />
    </div>

    {{-- Quick filters --}}
    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
        <button wire:click="$set('verified', {{ $verified ? 'false' : 'true' }})" type="button"
            class="inline-flex items-center gap-1 rounded-full border px-3 py-1.5 text-xs font-medium transition {{ $verified ? 'border-dark-gold bg-dark-gold/10 text-dark-gold' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Verified
        </button>
        <button wire:click="$set('openNow', {{ $openNow ? 'false' : 'true' }})" type="button"
            class="inline-flex items-center gap-1 rounded-full border px-3 py-1.5 text-xs font-medium transition {{ $openNow ? 'border-dark-gold bg-dark-gold/10 text-dark-gold' : 'border-gray-200 bg-white text-gray-600 hover:border-gray-300' }}">
            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
            Open now
        </button>
    </div>

    {{-- Filter bar --}}
    <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
        <div class="flex items-center gap-1 rounded-lg border border-gray-200 bg-white p-1">
            <button wire:click="$set('type', '')" type="button"
                class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $type === '' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:text-gray-900' }}">
                All
            </button>
            <button wire:click="$set('type', 'business')" type="button"
                class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $type === 'business' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:text-gray-900' }}">
                Businesses
            </button>
            <button wire:click="$set('type', 'product')" type="button"
                class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $type === 'product' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:text-gray-900' }}">
                Products
            </button>
            <button wire:click="$set('type', 'service')" type="button"
                class="rounded-md px-3 py-1.5 text-xs font-medium transition {{ $type === 'service' ? 'bg-gray-900 text-white' : 'text-gray-600 hover:text-gray-900' }}">
                Services
            </button>
        </div>

        <select wire:model.live="categoryId"
            class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">All categories</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="sort"
            class="rounded-lg border border-gray-300 px-3 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="relevance">Relevance</option>
            <option value="newest">Newest</option>
            <option value="rating">Top rated</option>
            <option value="popular">Popular</option>
        </select>
    </div>

    {{-- Results count --}}
    @if ($search || $type || $categoryId || $verified || $openNow)
        <p class="mt-6 text-center text-sm text-gray-500">
            {{ $totalCount }} {{ Str::plural('result', $totalCount) }} found
        </p>
    @endif

    {{-- Results grid --}}
    <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($results as $item)
            @if ($item['type'] === 'business')
                <a href="{{ $item['url'] }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                    <div class="aspect-[3/2] bg-gray-100 flex items-center justify-center">
                        @if ($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                        @else
                            <div class="flex h-full w-full items-center justify-center bg-gray-100 text-3xl font-bold text-gray-400">
                                {{ substr($item['name'], 0, 2) }}
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2">
                            @if ($item['verified'])
                                <span class="inline-flex items-center gap-0.5 rounded-full bg-dark-gold/10 px-2 py-0.5 text-[10px] font-medium text-dark-gold">
                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Verified
                                </span>
                            @endif
                            @if ($item['category'])
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">{{ $item['category'] }}</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $item['name'] }}</h3>
                        @if ($item['description'])
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $item['description'] }}</p>
                        @endif
                        <div class="mt-2 flex items-center gap-3">
                            @if ($item['rating'])
                                <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="h-3.5 w-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    {{ number_format($item['rating'], 1) }}
                                </span>
                            @endif
                            @if ($item['review_count'])
                                <span class="text-xs text-gray-400">({{ $item['review_count'] }})</span>
                            @endif
                            @if ($item['city'] || $item['state'])
                                <span class="text-xs text-gray-400">
                                    {{ collect([$item['city'], $item['state']])->filter()->join(', ') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
            @elseif ($item['type'] === 'product')
                <a href="{{ $item['url'] }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                    <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                        @if ($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                        @else
                            <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-medium text-blue-700">Product</span>
                            @if ($item['category'])
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">{{ $item['category'] }}</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $item['name'] }}</h3>
                        <p class="mt-1 text-xs text-gray-500">{{ $item['business_name'] }}</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($item['price'], 2) }} {{ $item['currency'] }}</p>
                    </div>
                </a>
            @elseif ($item['type'] === 'service')
                <a href="{{ $item['url'] }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                    <div class="aspect-[4/3] bg-gray-100 flex items-center justify-center">
                        @if ($item['image'])
                            <img src="{{ Storage::url($item['image']) }}" alt="{{ $item['name'] }}" class="h-full w-full object-cover">
                        @else
                            <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-medium text-purple-700">Service</span>
                            @if ($item['category'])
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">{{ $item['category'] }}</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $item['name'] }}</h3>
                        <p class="mt-1 text-xs text-gray-500">{{ $item['business_name'] }}</p>
                        <div class="mt-1 flex items-center gap-2">
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($item['price'], 2) }} {{ $item['currency'] }}</span>
                            @if ($item['duration_minutes'])
                                <span class="text-xs text-gray-400">{{ $item['duration_minutes'] }} min</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endif
        @empty
            <div class="col-span-full rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <p class="mt-4 text-sm text-gray-500">
                    @if ($search || $type || $categoryId || $verified || $openNow)
                        No results found. Try a different search or filter.
                    @else
                        No businesses or listings available yet. Check back later.
                    @endif
                </p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($totalCount > $perPage)
        <div class="mt-8 flex items-center justify-center gap-2">
            @if ($page > 1)
                <button wire:click="previousPage" type="button"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Previous
                </button>
            @endif
            <span class="text-sm text-gray-500">
                Page {{ $page }} of {{ $lastPage }}
            </span>
            @if ($hasMore)
                <button wire:click="nextPage" type="button"
                    class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Next
                </button>
            @endif
        </div>
    @endif
</div>
