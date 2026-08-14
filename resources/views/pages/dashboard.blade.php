{{--
  Dashboard home — content adapts to the current business type.
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
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <h2 class="text-lg font-semibold text-gray-900">Create your first business</h2>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a business profile.</p>
            <a href="{{ route('business.create') }}"
                class="mt-6 inline-block rounded-lg bg-gray-900 px-6 py-2 text-sm font-medium text-white hover:bg-gray-800">
                Create business
            </a>
        </div>
    @elseif ($business->website_url)
        {{-- Linked external business — simplified dashboard. --}}
        @php
            $locationCount = $business->locations()->count();
            $verification = $business->verification;
            $kycStatus = $verification?->status ?? 'unverified';
        @endphp

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0 flex-1">
                    <h2 class="text-sm font-semibold text-gray-900">Your external website</h2>
                    <p class="mt-0.5 truncate text-sm text-gray-500">{{ $business->website_url }}</p>
                </div>
                <div class="flex shrink-0 items-center gap-2">
                    <a href="{{ $business->website_url }}" target="_blank"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        View site →
                    </a>
                </div>
            </div>
        </div>

        @livewire(\App\Domains\Subscription\Livewire\SubscriptionStatus::class, key('dashboard-subscription'))

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Locations</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $locationCount }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ $locationCount === 1 ? 'Business location' : 'Business locations' }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">KYC Status</p>
                <div class="mt-2">
                    @if ($kycStatus === 'verified')
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2.5 py-1 text-xs font-medium text-green-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verified
                        </span>
                    @elseif ($kycStatus === 'pending' || $kycStatus === 'under_review')
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Under Review
                        </span>
                    @elseif ($kycStatus === 'rejected')
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2.5 py-1 text-xs font-medium text-red-700">Rejected</span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-500">
                            Not submitted — <a href="{{ route('dashboard.kyc') }}" class="underline">Complete KYC</a>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Locations</h3>
                    <a href="{{ route('dashboard.locations') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                <p class="mt-1 text-sm text-gray-500">{{ $locationCount }} location{{ $locationCount !== 1 ? 's' : '' }}</p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">KYC Verification</h3>
                    <a href="{{ route('dashboard.kyc') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                <p class="mt-1 text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $kycStatus)) }}</p>
            </div>
        </div>
    @else
        @php
            $publicUrl = route('business.profile', $business);
            $productCount = $business->isProductStore() ? $business->products()->where('is_active', true)->count() : 0;
            $serviceCount = $business->isServiceBusiness() ? $business->services()->where('is_active', true)->count() : 0;
            $visitorCount = \App\Models\AnalyticsEvent::where('business_id', $business->id)->where('type', 'page_view')->count();
            $viewsByDay = app(\App\Domains\Analytics\Actions\GenerateReport::class)->viewsByDay($business, 7);
            $todayViews = \App\Models\AnalyticsEvent::where('business_id', $business->id)->where('type', 'page_view')->whereDate('created_at', today())->count();
            $yesterdayViews = \App\Models\AnalyticsEvent::where('business_id', $business->id)->where('type', 'page_view')->whereDate('created_at', today()->subDay())->count();
            $sparklineData = array_values($viewsByDay);
            $sparklineLabels = array_map(fn($d) => \Carbon\Carbon::parse($d)->format('D'), array_keys($viewsByDay));
            $trafficSources = \App\Models\AnalyticsEvent::where('business_id', $business->id)
                ->selectRaw('source, COUNT(*) as count')
                ->groupBy('source')
                ->orderByDesc('count')
                ->pluck('count', 'source')
                ->toArray();
            $trafficChartConfig = [
                'type' => 'doughnut',
                'labels' => array_map(fn($s) => $s ? ucfirst(str_replace('_', ' ', $s)) : 'Direct', array_keys($trafficSources)) ?: ['No data'],
                'datasets' => [[
                    'data' => array_values($trafficSources) ?: [1],
                    'backgroundColor' => ['#3b82f6','#10b981','#f59e0b','#ef4444','#8b5cf6','#ec4899','#06b6d4'],
                    'borderWidth' => 0,
                ]],
                'legend' => true,
            ];
        @endphp

        {{-- Live URL --}}
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
                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">Open</a>
                    <button @click="await navigator.clipboard.writeText('{{ $publicUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="rounded-lg border border-gray-200 bg-white px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <span x-show="!copied">Copy link</span>
                        <span x-show="copied" class="text-green-600">Copied!</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Subscription status --}}
        <div>
            @livewire(\App\Domains\Subscription\Livewire\SubscriptionStatus::class, key('dashboard-subscription-hosted'))
        </div>

        {{-- Stat cards with sparkline --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Page Views</p>
                    <span class="text-xs {{ $todayViews >= $yesterdayViews ? 'text-green-600' : 'text-red-600' }}">
                        {{ $todayViews >= $yesterdayViews ? '▲' : '▼' }} {{ $todayViews }} today
                    </span>
                </div>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($visitorCount) }}</p>
                <div class="mt-2" x-data="chart($el)" data-chart='{{ json_encode(["type"=>"line","labels"=>$sparklineLabels,"datasets"=>[["data"=>$sparklineData,"borderColor"=>"#3b82f6","backgroundColor"=>"transparent","pointRadius"=>0,"borderWidth"=>2,"fill"=>false]],"legend"=>false,"options"=>["scales"=>["x"=>["display"=>false],"y"=>["display"=>false]],"plugins"=>["tooltip"=>["enabled"=>false]]]]) }}'>
                    <div style="height:40px"><canvas></canvas></div>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $business->isProductStore() ? 'Products' : 'Services' }}</p>
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $business->isProductStore() ? $productCount : $serviceCount }}</p>
                <p class="mt-1 text-xs text-gray-400">{{ $business->isProductStore() ? 'Active products' : 'Active services' }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">{{ $business->isProductStore() ? 'Products' : 'Services' }} views</p>
                @php
                    $itemViewsTotal = $business->isProductStore()
                        ? app(\App\Domains\Analytics\Actions\GenerateReport::class)->totalProductViews($business)
                        : app(\App\Domains\Analytics\Actions\GenerateReport::class)->totalServiceViews($business);
                @endphp
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ number_format($itemViewsTotal) }}</p>
            </div>

            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Conversion</p>
                @php $conversionRate = $visitorCount > 0 ? round(($itemViewsTotal / $visitorCount) * 100, 1) : 0; @endphp
                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $conversionRate }}%</p>
                <p class="mt-1 text-xs text-gray-400">Views to {{ $business->isProductStore() ? 'product' : 'service' }}</p>
            </div>
        </div>

        {{-- Main charts --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            @livewire(\App\Domains\Analytics\Livewire\VisitorsChart::class, ['business' => $business, 'days' => 7], key('dashboard-chart'))

            <div class="rounded-lg border border-gray-200 bg-white p-5" x-data="chart($el)" data-chart='{{ json_encode($trafficChartConfig) }}'>
                <h3 class="text-sm font-semibold text-gray-900">Traffic Sources</h3>
                <p class="mt-0.5 text-xs text-gray-500">Where visitors come from</p>
                <div class="mt-4" style="height: 200px;"><canvas></canvas></div>
            </div>
        </div>

        {{-- Quick links --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @if ($business->isProductStore())
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Products</h3>
                        <a href="{{ route('dashboard.products') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $productCount }} active product{{ $productCount !== 1 ? 's' : '' }}</p>
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-white p-5">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-gray-900">Services</h3>
                        <a href="{{ route('dashboard.services') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">{{ $serviceCount }} active service{{ $serviceCount !== 1 ? 's' : '' }}</p>
                </div>
            @endif
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Promotions</h3>
                    <a href="{{ route('dashboard.promotions') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                @php $activePromos = \App\Models\Promotion::where('business_id', $business->id)->running()->count(); @endphp
                <p class="mt-1 text-sm text-gray-500">{{ $activePromos }} active promotion{{ $activePromos !== 1 ? 's' : '' }}</p>
            </div>

            {{-- Website --}}
            @php
                $website = $business->website;
                $pageCount = $website ? $website->pages()->count() : 0;
            @endphp
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Website</h3>
                    <a href="{{ route('dashboard.website') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                @if ($website)
                    <p class="mt-1 text-sm text-gray-500">{{ $pageCount }} page{{ $pageCount !== 1 ? 's' : '' }} published</p>
                @else
                    <p class="mt-1 text-sm text-gray-400">No website yet</p>
                @endif
            </div>

            {{-- Locations --}}
            @php $locationCount = $business->locations()->count(); @endphp
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Locations</h3>
                    <a href="{{ route('dashboard.locations') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                <p class="mt-1 text-sm text-gray-500">{{ $locationCount }} location{{ $locationCount !== 1 ? 's' : '' }}</p>
            </div>

            {{-- KYC Verification --}}
            @php
                $verification = $business->verification;
                $kycStatus = $verification?->status ?? 'unverified';
            @endphp
            <div class="rounded-lg border border-gray-200 bg-white p-5">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">KYC Verification</h3>
                    <a href="{{ route('dashboard.kyc') }}" class="text-xs font-medium text-gray-600 hover:text-gray-900">Manage →</a>
                </div>
                <div class="mt-1.5">
                    @if ($kycStatus === 'verified')
                        <span class="inline-flex items-center gap-1 rounded-full bg-green-50 px-2 py-0.5 text-xs font-medium text-green-700">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Verified
                        </span>
                    @elseif ($kycStatus === 'pending' || $kycStatus === 'under_review')
                        <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Under Review
                        </span>
                    @elseif ($kycStatus === 'rejected')
                        <span class="inline-flex items-center gap-1 rounded-full bg-red-50 px-2 py-0.5 text-xs font-medium text-red-700">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Rejected
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">
                            Not submitted
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
