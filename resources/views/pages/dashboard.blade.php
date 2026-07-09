{{--
  Dashboard home — content adapts to the current business type.
  Product stores see products-focused dashboard.
  Service businesses see services/portfolio dashboard.
--}}
@extends('layouts.dashboard')

@section('title', 'Dashboard')

@php
    $user = auth()->user();
    $businesses = $user->businesses;
    $business = $businesses->firstWhere('id', session('current_business_id')) ?: $businesses->first();
@endphp

@section('content')
<div class="space-y-6">
    {{-- Header. --}}
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">
            {{ $business?->name ?? 'Dashboard' }}
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Welcome back, {{ $user->name }}.
            @if ($business)
                &middot; {{ $business->isProductStore() ? 'Product store' : 'Service business' }}
            @endif
        </p>
    </div>

    @if (! $business)
        {{-- No business yet — prompt to create one. --}}
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <h2 class="text-lg font-semibold text-gray-900">Create your first business</h2>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a business profile.</p>
            <a href="{{ route('business.create') }}"
                class="mt-6 inline-block rounded-lg bg-gray-900 px-6 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Create business
            </a>
        </div>
    @else
        {{-- View & share your public profile. --}}
        @php $publicUrl = route('business.profile', $business); @endphp
        <div class="rounded-lg border border-gray-200 bg-white p-6" x-data="{ copied: false }">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h2 class="text-sm font-semibold text-gray-900">
                        {{ $business->isProductStore() ? 'Your store' : 'Your business' }} is live
                    </h2>
                    <p class="mt-0.5 truncate text-sm text-gray-500">{{ $publicUrl }}</p>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <a href="{{ $publicUrl }}" target="_blank"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Open
                    </a>
                    <button @click="await navigator.clipboard.writeText('{{ $publicUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <span x-show="!copied">Copy link</span>
                        <span x-show="copied" class="text-green-600">Copied!</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if ($business)
        <div class="mt-6">
            @livewire(\App\Domains\Subscription\Livewire\SubscriptionStatus::class, key('dashboard-subscription'))
        </div>
    @endif

    @if ($business && $business->isProductStore())
        {{-- ═══════════════════════════════════════════════
             PRODUCT STORE DASHBOARD
             Shows products overview, inventory stats, analytics.
             ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Products</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $business->products()->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Active</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $business->products()->where('is_active', true)->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Visitors</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ \App\Models\AnalyticsEvent::where('business_id', $business->id)->where('type', 'page_view')->count() }}
                </p>
            </div>
        </div>

        @livewire(\App\Domains\Analytics\Livewire\VisitorsChart::class, ['business' => $business, 'days' => 7], key('dashboard-chart'))

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Your products</h2>
                <a href="{{ route('dashboard.products') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Manage &rarr;</a>
            </div>
            <p class="mt-2 text-sm text-gray-500">{{ $business->products()->count() }} product{{ $business->products()->count() !== 1 ? 's' : '' }}.</p>
        </div>

    @elseif ($business && $business->isServiceBusiness())
        {{-- ═══════════════════════════════════════════════
             SERVICE BUSINESS DASHBOARD
             Shows services overview, portfolio stats, analytics.
             ═══════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Services</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $business->services()->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Active</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $business->services()->where('is_active', true)->count() }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <p class="text-sm font-medium text-gray-500">Visitors</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">
                    {{ \App\Models\AnalyticsEvent::where('business_id', $business->id)->where('type', 'page_view')->count() }}
                </p>
            </div>
        </div>

        @livewire(\App\Domains\Analytics\Livewire\VisitorsChart::class, ['business' => $business, 'days' => 7], key('dashboard-chart'))

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Your services</h2>
                <a href="{{ route('dashboard.services') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Manage &rarr;</a>
            </div>
            <p class="mt-2 text-sm text-gray-500">{{ $business->services()->count() }} service{{ $business->services()->count() !== 1 ? 's' : '' }}.</p>
        </div>
    @endif
</div>
@endsection
