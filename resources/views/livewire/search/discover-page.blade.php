<div>
    {{-- Featured Businesses --}}
    @if (count($featured) > 0)
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-gray-900">Featured Businesses</h2>
            <p class="mt-1 text-sm text-gray-500">Top-rated, verified businesses on the platform</p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featured as $business)
                    <a href="{{ route('business.profile', $business['slug']) }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 hover:shadow-md transition">
                        <div class="h-12 w-12 shrink-0 overflow-hidden rounded-full bg-gray-100">
                            @if ($business['logo'])
                                <img src="{{ Storage::url($business['logo']) }}" alt="{{ $business['name'] }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-sm font-bold text-gray-400">
                                    {{ substr($business['name'], 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="truncate text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $business['name'] }}</h3>
                                @if ($business['is_verified'])
                                    <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <p class="mt-0.5 text-xs text-gray-500">
                                @if ($business['average_rating'])
                                    <span class="text-amber-500">{{ number_format($business['average_rating'], 1) }}</span> &middot;
                                @endif
                                {{ $business['city'] ?? $business['state'] ?? '' }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Trending Businesses --}}
    @if (count($trendingBusinesses) > 0)
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-gray-900">Trending Businesses</h2>
            <p class="mt-1 text-sm text-gray-500">Popular businesses near you</p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($trendingBusinesses as $business)
                    <a href="{{ route('business.profile', $business['slug']) }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                        <div class="aspect-[3/2] bg-gray-100">
                            @if ($business['cover'])
                                <img src="{{ Storage::url($business['cover']) }}" alt="{{ $business['name'] }}" class="h-full w-full object-cover">
                            @elseif ($business['logo'])
                                <img src="{{ Storage::url($business['logo']) }}" alt="{{ $business['name'] }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-2xl font-bold text-gray-300">{{ substr($business['name'], 0, 2) }}</div>
                            @endif
                        </div>
                        <div class="p-4">
                            <div class="flex items-center gap-2">
                                <h3 class="text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $business['name'] }}</h3>
                                @if ($business['is_verified'])
                                    <svg class="h-4 w-4 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                @endif
                            </div>
                            <p class="mt-1 text-xs text-gray-500">{{ $business['city'] ?? '' }}{{ $business['city'] && $business['state'] ? ', ' : '' }}{{ $business['state'] ?? '' }}</p>
                            @if ($business['average_rating'])
                                <div class="mt-2 flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    <span class="text-xs text-gray-500">{{ number_format($business['average_rating'], 1) }} ({{ $business['review_count'] }})</span>
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Trending Products --}}
    @if (count($trendingProducts) > 0)
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-gray-900">Trending Products</h2>
            <p class="mt-1 text-sm text-gray-500">Latest products from verified sellers</p>

            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                @foreach ($trendingProducts as $product)
                    <a href="{{ route('business.product', [$product['business']['slug'], $product['slug']]) }}" class="group rounded-xl border border-gray-200 bg-white overflow-hidden hover:shadow-md transition">
                        <div class="aspect-square bg-gray-100">
                            @if (is_array($product['images']) && count($product['images']))
                                <img src="{{ Storage::url($product['images'][0]) }}" alt="{{ $product['name'] }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-8 w-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-3">
                            <h3 class="text-xs font-semibold text-gray-900 group-hover:text-gray-700 line-clamp-2">{{ $product['name'] }}</h3>
                            <p class="mt-1 text-[10px] text-gray-500">{{ $product['business']['name'] }}</p>
                            <p class="mt-1 text-sm font-semibold text-gray-900">{{ number_format($product['price'], 2) }} {{ $product['currency'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Trending Services --}}
    @if (count($trendingServices) > 0)
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-gray-900">Trending Services</h2>
            <p class="mt-1 text-sm text-gray-500">Popular services available now</p>

            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($trendingServices as $service)
                    <a href="{{ route('business.service', [$service['business']['slug'], $service['slug']]) }}" class="group flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 hover:shadow-md transition">
                        <div class="h-10 w-10 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                            @if (is_array($service['images']) && count($service['images']))
                                <img src="{{ Storage::url($service['images'][0]) }}" alt="{{ $service['name'] }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center">
                                    <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <h3 class="truncate text-sm font-semibold text-gray-900 group-hover:text-gray-700">{{ $service['name'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $service['business']['name'] }}</p>
                        </div>
                        <span class="shrink-0 text-sm font-semibold text-gray-900">{{ number_format($service['price'], 2) }} {{ $service['currency'] }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Browse by Category --}}
    @if (count($browseByCategory) > 0)
        <section class="mb-12">
            <h2 class="text-lg font-semibold text-gray-900">Browse by Category</h2>
            <p class="mt-1 text-sm text-gray-500">Discover businesses in popular categories</p>

            <div class="mt-4 space-y-8">
                @foreach ($browseByCategory as $category)
                    <div>
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-gray-900">
                                @if ($category['icon'])
                                    <span class="mr-1">{{ $category['icon'] }}</span>
                                @endif
                                {{ $category['name'] }}
                            </h3>
                        </div>

                        <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                            @foreach ($category['businesses'] as $business)
                                <a href="{{ route('business.profile', $business['slug']) }}" class="group flex items-center gap-3 rounded-lg border border-gray-100 bg-white p-3 hover:border-gray-200 hover:shadow-sm transition">
                                    <div class="h-8 w-8 shrink-0 overflow-hidden rounded-full bg-gray-100">
                                        @if ($business['logo'])
                                            <img src="{{ Storage::url($business['logo']) }}" alt="{{ $business['name'] }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="flex h-full w-full items-center justify-center text-xs font-bold text-gray-400">{{ substr($business['name'], 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-medium text-gray-900 group-hover:text-gray-700">{{ $business['name'] }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $business['city'] ?? '' }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
