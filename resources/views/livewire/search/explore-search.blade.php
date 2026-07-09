<div>
    {{-- Search and filters --}}
    <div class="mx-auto max-w-xl">
        <div class="relative">
            <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
            </svg>
            <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search businesses, products, services..."
                class="block w-full rounded-full border border-gray-300 bg-white py-3 pl-12 pr-4 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="mt-6 flex flex-wrap items-center justify-center gap-3">
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
    </div>

    {{-- Results count --}}
    @if ($search || $type || $categoryId)
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
                            <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">Business</span>
                            @if ($item['category'])
                                <span class="rounded-full bg-gray-100 px-2 py-0.5 text-[10px] font-medium text-gray-600">{{ $item['category'] }}</span>
                            @endif
                        </div>
                        <h3 class="mt-2 text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $item['name'] }}</h3>
                        @if ($item['description'])
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ $item['description'] }}</p>
                        @endif
                        @if ($item['city'] || $item['state'])
                            <p class="mt-2 text-xs text-gray-400">
                                {{ collect([$item['city'], $item['state']])->filter()->join(', ') }}
                            </p>
                        @endif
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
                    @if ($search || $type || $categoryId)
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
