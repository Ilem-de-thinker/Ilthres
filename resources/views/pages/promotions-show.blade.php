@extends('layouts.app')

@section('title', $promotion->name . ' - ' . ($business->name ?? 'Promotions'))

@section('content')
<div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
    {{-- Hero Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-gray-900 to-gray-700 px-8 py-16 text-white">
        @if ($promotion->banner_image)
            <img src="{{ $promotion->banner_image }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-30">
        @endif
        <div class="relative">
            @php
                $badgeColors = ['red' => 'bg-red-500', 'orange' => 'bg-orange-500', 'green' => 'bg-green-500', 'purple' => 'bg-purple-500', 'blue' => 'bg-blue-500', 'black' => 'bg-gray-900'];
                $badgeColor = $badgeColors[$promotion->badge_color] ?? 'bg-red-500';
            @endphp
            <span class="{{ $badgeColor }} inline-flex items-center rounded-full px-3 py-1 text-sm font-bold uppercase tracking-wide">{{ $promotion->badge_text ?? $promotion->name }}</span>
            <h1 class="mt-4 text-4xl font-bold tracking-tight">{{ $promotion->name }}</h1>
            @if ($promotion->description)
                <p class="mt-2 max-w-2xl text-lg text-gray-200">{{ $promotion->description }}</p>
            @endif

            <div class="mt-6 flex flex-wrap items-center gap-6">
                @if ($promotion->ends_at)
                    <div class="flex items-center gap-2">
                        <svg class="h-5 w-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-sm">
                            @if ($promotion->hasExpired())
                                Promotion ended {{ $promotion->ends_at->format('M j, Y') }}
                            @else
                                Ends {{ $promotion->ends_at->format('M j, Y g:i A') }}
                            @endif
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Products Grid --}}
    @if ($products->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900">Discounted Products</h2>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="relative aspect-square overflow-hidden bg-gray-100">
                            @if ($promotion->show_badge)
                                <x-promotion-badge :promotion="$promotion" />
                            @endif
                            @if ($product->images && count($product->images) > 0)
                                <img src="{{ $product->images[0] }}" alt="{{ $product->name }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif

                            @if ($promotion->show_countdown && $promotion->ends_at && ! $promotion->hasExpired())
                                <div class="absolute bottom-2 left-2 right-2">
                                    <div class="rounded-lg bg-black/70 px-3 py-2 text-center text-xs text-white">
                                        Ends in {{ $promotion->daysRemaining() }} days
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 space-y-2">
                            <h3 class="text-sm font-semibold text-gray-900 line-clamp-1">{{ $product->name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-gray-900">₦{{ number_format($promotion->getDiscountedPrice((float) $product->price), 2) }}</span>
                                @if ($promotion->getDiscountPercent((float) $product->price))
                                    <span class="text-xs font-medium text-red-600">-{{ $promotion->getDiscountPercent((float) $product->price) }}%</span>
                                @endif
                            </div>
                            @if ($promotion->promotion_type !== 'fixed_price' && (float) $product->price > 0)
                                <p class="text-sm text-gray-400 line-through">₦{{ number_format($product->price, 2) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Services Grid --}}
    @if ($services->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900">Discounted Services</h2>
            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($services as $service)
                    <div class="group relative overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="relative aspect-video overflow-hidden bg-gray-100">
                            @if ($promotion->show_badge)
                                <x-promotion-badge :promotion="$promotion" />
                            @endif
                            @if ($service->images && count($service->images) > 0)
                                <img src="{{ $service->images[0] }}" alt="{{ $service->name }}" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full items-center justify-center text-gray-400">
                                    <svg class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                </div>
                            @endif
                        </div>

                        <div class="p-4 space-y-2">
                            <h3 class="text-sm font-semibold text-gray-900">{{ $service->name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-gray-900">₦{{ number_format($promotion->getDiscountedPrice((float) $service->price), 2) }}</span>
                                @if ($promotion->getDiscountPercent((float) $service->price))
                                    <span class="text-xs font-medium text-red-600">-{{ $promotion->getDiscountPercent((float) $service->price) }}%</span>
                                @endif
                            </div>
                            @if ($promotion->promotion_type !== 'fixed_price' && (float) $service->price > 0)
                                <p class="text-sm text-gray-400 line-through">₦{{ number_format($service->price, 2) }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if ($products->isEmpty() && $services->isEmpty())
        <div class="mt-12 text-center">
            <p class="text-gray-500">No items currently available in this promotion.</p>
        </div>
    @endif
</div>
@endsection
