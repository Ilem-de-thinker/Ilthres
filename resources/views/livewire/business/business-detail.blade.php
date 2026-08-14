<div x-data="{ tab: 'overview' }" class="min-h-screen bg-gray-50">

    @php
        $heroImages = [
            'fashion' => 'https://images.unsplash.com/photo-1558618666-fcd25c85f82e?w=1400&h=500&fit=crop&crop=center',
            'food' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400&h=500&fit=crop&crop=center',
            'technology' => 'https://images.unsplash.com/photo-1518770660439-4636190af475?w=1400&h=500&fit=crop&crop=center',
            'health' => 'https://images.unsplash.com/photo-1576091160550-2173dba999ef?w=1400&h=500&fit=crop&crop=center',
            'automotive' => 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=1400&h=500&fit=crop&crop=center',
            'beauty' => 'https://images.unsplash.com/photo-1560066984-138dadb4c035?w=1400&h=500&fit=crop&crop=center',
            'education' => 'https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?w=1400&h=500&fit=crop&crop=center',
            'default' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1400&h=500&fit=crop&crop=center',
        ];
        $categorySlug = strtolower($business->category->slug ?? '');
        $heroImage = $heroImages['default'];
        foreach ($heroImages as $key => $url) {
            if (str_contains($categorySlug, $key)) {
                $heroImage = $url;
                break;
            }
        }
    @endphp

    {{-- Hero --}}
    <div class="relative bg-gray-900">
        @if ($business->cover)
            <div class="h-64 sm:h-80 lg:h-96">
                <img src="{{ Storage::url($business->cover) }}" alt="{{ $business->name }}" class="h-full w-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/30 to-gray-900/10"></div>
            </div>
        @else
            <div class="h-64 sm:h-80 lg:h-96">
                <img src="{{ $heroImage }}" alt="{{ $business->name }}" class="h-full w-full object-cover opacity-50">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900/90 via-gray-900/50 to-gray-900/20"></div>
            </div>
        @endif

        {{-- Profile card overlapping cover --}}
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="{{ $business->cover ? '-mt-20 relative' : '-mt-16 relative' }}">
                <div class="flex flex-col items-center gap-5 sm:flex-row sm:items-end">
                    {{-- Logo --}}
                    <div class="shrink-0">
                        @if ($business->logo)
                            <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->name }}"
                                class="h-24 w-24 rounded-2xl border-4 border-gray-900 object-cover shadow-xl sm:h-28 sm:w-28">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl border-4 border-gray-900 bg-gradient-to-br from-dark-gold to-amber-600 text-3xl font-bold text-white shadow-xl sm:h-28 sm:w-28">
                                {{ strtoupper(substr($business->name, 0, 2)) }}
                            </div>
                        @endif
                    </div>

                    {{-- Name + meta --}}
                    <div class="flex-1 pb-1 text-center sm:text-left">
                        <div class="flex items-center gap-2.5 justify-center sm:justify-start flex-wrap">
                            <h1 class="text-2xl font-bold tracking-tight text-white drop-shadow-lg sm:text-3xl">{{ $business->name }}</h1>
                            @if ($business->is_verified)
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500 px-2.5 py-0.5 text-xs font-semibold text-white shadow">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Verified
                                </span>
                            @endif
                        </div>
                        <div class="mt-1.5 flex items-center gap-2 justify-center sm:justify-start text-sm text-white/80">
                            @if ($business->category)
                                <span class="rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium backdrop-blur-sm">{{ $business->category->name }}</span>
                            @endif
                            @if ($business->city || $business->state)
                                <span class="flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                    {{ collect([$business->city, $business->state])->filter()->join(', ') }}
                                </span>
                            @endif
                            <span class="rounded-full bg-white/15 px-2.5 py-0.5 text-xs font-medium backdrop-blur-sm">{{ $business->isProductStore() ? 'Store' : 'Service' }}</span>
                        </div>
                    </div>

                    {{-- Action buttons --}}
                    <div class="flex items-center gap-2 pb-1">
                        <a href="{{ $this->getSiteUrl() }}"
                            @if ($this->hasExternalSite()) target="_blank" rel="noopener" @endif
                            class="inline-flex items-center gap-2 rounded-xl bg-dark-gold px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-dark-gold/25 transition hover:bg-dark-gold-500 hover:shadow-dark-gold/40">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                            View Site
                        </a>
                        @if ($business->phone)
                            <a href="tel:{{ $business->phone }}"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/20">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                            </a>
                        @endif
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ $business->whatsapp }}" target="_blank" rel="noopener"
                                class="inline-flex items-center gap-1.5 rounded-xl border border-white/20 bg-white/10 px-4 py-2.5 text-sm font-medium text-white backdrop-blur-sm transition hover:bg-white/20">
                                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom spacer to let hero bleed into page --}}
        <div class="h-8 bg-gray-50"></div>
    </div>

    {{-- Tab bar --}}
    <div class="sticky top-0 z-30 border-b border-gray-200 bg-white/95 backdrop-blur-sm">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <nav class="-mb-px flex gap-1 overflow-x-auto" aria-label="Tabs">
                @php
                    $tabs = [
                        'overview' => 'Overview',
                        'items' => $business->isProductStore() ? 'Products' : 'Services',
                        'reviews' => 'Reviews',
                        'contact' => 'Contact',
                    ];
                @endphp
                @foreach ($tabs as $key => $label)
                    <button
                        x-on:click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}' ? 'border-dark-gold text-dark-gold' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700'"
                        class="whitespace-nowrap border-b-2 px-4 py-3 text-sm font-medium transition">
                        {{ $label }}
                    </button>
                @endforeach
            </nav>
        </div>
    </div>

    {{-- Tab panels --}}
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">

        {{-- Overview --}}
        <div x-show="tab === 'overview'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="space-y-6">
                {{-- About --}}
                @if ($business->description || $business->about)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-gray-900">About</h2>
                        <p class="mt-3 text-sm leading-relaxed text-gray-600 whitespace-pre-line">{{ $business->about ?: $business->description }}</p>
                    </div>
                @endif

                {{-- Quick info grid --}}
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0V6a3 3 0 013-3h12a3 3 0 013 3v3.349"/></svg>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-gray-900">{{ $business->isProductStore() ? 'Store' : 'Service' }}</p>
                        <p class="text-xs text-gray-400">Business type</p>
                    </div>
                    @if ($business->category)
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-purple-50 text-purple-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $business->category->name }}</p>
                            <p class="text-xs text-gray-400">Category</p>
                        </div>
                    @endif
                    @if ($primaryLocation)
                        <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center">
                            <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <p class="mt-2 text-sm font-semibold text-gray-900">{{ $primaryLocation->name }}</p>
                            <p class="text-xs text-gray-400">{{ $primaryLocation->city ?? $primaryLocation->state ?? 'Location' }}</p>
                        </div>
                    @endif
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 text-center">
                        <div class="mx-auto flex h-10 w-10 items-center justify-center rounded-xl {{ $business->is_verified ? 'bg-emerald-50 text-emerald-600' : 'bg-gray-100 text-gray-400' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-gray-900">{{ $business->is_verified ? 'Verified' : 'Unverified' }}</p>
                        <p class="text-xs text-gray-400">Trust status</p>
                    </div>
                </div>

                {{-- Social links --}}
                @if ($business->social_links && count(array_filter($business->social_links)))
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-gray-900">Social</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($business->social_links as $platform => $url)
                                @if ($url)
                                    <a href="{{ $url }}" target="_blank" rel="noopener"
                                        class="inline-flex items-center gap-2 rounded-xl border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-600 transition hover:border-gray-300 hover:bg-gray-50 hover:text-gray-900">
                                        @if ($platform === 'instagram')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                        @elseif ($platform === 'facebook')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                        @elseif ($platform === 'twitter' || $platform === 'x')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                        @elseif ($platform === 'tiktok')
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                        @else
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                        @endif
                                        {{ ucfirst($platform) }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Products / Services --}}
        <div x-show="tab === 'items'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            @if ($products->isNotEmpty() || $services->isNotEmpty())
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($products as $product)
                        <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white transition hover:shadow-lg">
                            <div class="aspect-[4/3] bg-gray-100">
                                @if ($product->images[0] ?? null)
                                    <img src="{{ Storage::url($product->images[0]) }}" alt="{{ $product->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $product->name }}</h3>
                                <p class="mt-1 text-base font-bold text-dark-gold">{{ number_format($product->price, 2) }} <span class="text-xs font-medium text-gray-400">{{ $product->currency }}</span></p>
                            </div>
                        </div>
                    @endforeach

                    @foreach ($services as $service)
                        <div class="group overflow-hidden rounded-2xl border border-gray-200 bg-white transition hover:shadow-lg">
                            <div class="aspect-[4/3] bg-gray-100">
                                @if ($service->images[0] ?? null)
                                    <img src="{{ Storage::url($service->images[0]) }}" alt="{{ $service->name }}" class="h-full w-full object-cover transition group-hover:scale-105">
                                @else
                                    <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-50">
                                        <svg class="h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19 14.5m-4.25-11.396c.251.023.501.05.75.082M5 14.5l-1.435 1.435A2.25 2.25 0 005 18.25h14a2.25 2.25 0 001.435-2.315L19 14.5m-14 0h14"/></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $service->name }}</h3>
                                <div class="mt-1 flex items-center gap-2">
                                    <span class="text-base font-bold text-dark-gold">{{ number_format($service->price, 2) }} <span class="text-xs font-medium text-gray-400">{{ $service->currency }}</span></span>
                                    @if ($service->duration_minutes)
                                        <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs text-gray-500">{{ $service->duration_minutes }} min</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <p class="mt-3 text-sm text-gray-500">No {{ $business->isProductStore() ? 'products' : 'services' }} listed yet.</p>
                </div>
            @endif
        </div>

        {{-- Reviews --}}
        <div x-show="tab === 'reviews'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="rounded-2xl border border-dashed border-gray-300 bg-white p-12 text-center">
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-amber-50">
                    <svg class="h-6 w-6 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
                </div>
                <h3 class="mt-3 text-sm font-semibold text-gray-900">No reviews yet</h3>
                <p class="mt-1 text-sm text-gray-500">Reviews from customers will appear here.</p>
            </div>
        </div>

        {{-- Contact --}}
        <div x-show="tab === 'contact'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="space-y-6">
                {{-- Contact details --}}
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-base font-semibold text-gray-900">Get in touch</h2>
                    <div class="mt-4 space-y-4">
                        @if ($business->email)
                            <a href="mailto:{{ $business->email }}" class="flex items-center gap-4 rounded-xl p-3 transition hover:bg-gray-50">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $business->email }}</p>
                                    <p class="text-xs text-gray-400">Email</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        @endif
                        @if ($business->phone)
                            <a href="tel:{{ $business->phone }}" class="flex items-center gap-4 rounded-xl p-3 transition hover:bg-gray-50">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $business->phone }}</p>
                                    <p class="text-xs text-gray-400">Phone</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        @endif
                        @if ($business->whatsapp)
                            <a href="https://wa.me/{{ $business->whatsapp }}" target="_blank" rel="noopener" class="flex items-center gap-4 rounded-xl p-3 transition hover:bg-gray-50">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $business->whatsapp }}</p>
                                    <p class="text-xs text-gray-400">WhatsApp</p>
                                </div>
                                <svg class="h-4 w-4 shrink-0 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                            </a>
                        @endif
                        @if ($business->address)
                            <div class="flex items-start gap-4 rounded-xl p-3">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $business->address }}</p>
                                    <p class="text-xs text-gray-400">{{ collect([$business->city, $business->state, $business->country])->filter()->join(', ') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Location --}}
                @if ($primaryLocation)
                    <div class="rounded-2xl border border-gray-200 bg-white p-6">
                        <h2 class="text-base font-semibold text-gray-900">Location</h2>
                        <div class="mt-3 flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $primaryLocation->name }}</p>
                                <p class="text-sm text-gray-500">{{ $primaryLocation->address }}{{ $primaryLocation->city ? ', ' . $primaryLocation->city : '' }}{{ $primaryLocation->state ? ', ' . $primaryLocation->state : '' }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
