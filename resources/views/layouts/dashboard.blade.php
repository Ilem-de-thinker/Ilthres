<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ilthres Market') }} — @yield('title', 'Dashboard')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        .sidebar-active { background-color: rgb(243 244 246); color: rgb(17 24 39); font-weight: 600; }
        .sidebar-inactive { color: rgb(55 65 81); }
        .sidebar-inactive:hover { background-color: rgb(243 244 246); color: rgb(17 24 39); }
        .tab-active { color: rgb(17 24 39); }
        .tab-inactive { color: rgb(156 163 175); }
        .tab-inactive:hover { color: rgb(107 114 128); }
        @media (max-width: 1023px) {
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased" x-data="dashboardLayout()" x-init="init()">

    @php $user = auth()->user(); $businesses = $user->businesses; $currentBusiness = $businesses->firstWhere('id', session('current_business_id')) ?: $businesses->first(); @endphp

    {{-- Mobile slide-over drawer. --}}
    <div x-show="drawerOpen" class="fixed inset-0 z-50 lg:hidden" x-cloak>
        <div x-show="drawerOpen" @click="drawerOpen = false" class="fixed inset-0 bg-black/40" x-transition.opacity></div>
        <div x-show="drawerOpen" @click.outside="drawerOpen = false" class="fixed inset-y-0 left-0 z-50 w-72 max-w-[80vw] border-r border-gray-200 bg-white shadow-xl"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
            <div class="flex h-full flex-col">
                <div class="flex items-center justify-between border-b border-gray-200 px-4 py-4">
                    <span class="text-lg font-bold tracking-tight text-gray-900">Ilthres</span>
                    <button @click="drawerOpen = false" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                    <p class="px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Main</p>
                    <a href="{{ route('dashboard') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Dashboard
                    </a>
                    <a href="{{ route('dashboard.business.edit') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.business*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Business
                    </a>
                    @if ($currentBusiness?->isProductStore())
                        <a href="{{ route('dashboard.products') }}" @click="drawerOpen = false"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.products*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            Products
                        </a>
                    @elseif ($currentBusiness?->isServiceBusiness())
                        <a href="{{ route('dashboard.services') }}" @click="drawerOpen = false"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.services*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            Services
                        </a>
                    @endif
                    <a href="{{ route('dashboard.media') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.media') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Media
                    </a>
                    <a href="{{ route('dashboard.promotions') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.promotions*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Promotions
                    </a>
                    <a href="{{ route('dashboard.theme') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.theme*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Themes
                    </a>

                    <p class="mt-6 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Insights</p>
                    <a href="{{ route('dashboard.analytics') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.analytics*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analytics
                    </a>

                    <p class="mt-6 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Plan & Billing</p>
                    <a href="{{ route('dashboard.subscription') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.subscription*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Subscription
                    </a>
                    <a href="{{ route('dashboard.billing') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.billing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Billing
                    </a>

                    <p class="mt-6 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Marketing</p>
                    <a href="{{ route('dashboard.marketing') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.marketing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Marketing
                    </a>

                    <p class="mt-6 px-3 text-xs font-semibold uppercase tracking-wider text-gray-400">Other</p>
                    <a href="{{ route('dashboard.notifications') }}" @click="drawerOpen = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.notifications*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifications
                    </a>

                    @if ($currentBusiness)
                        <hr class="my-4 border-gray-200">
                        <a href="{{ route('business.profile', $currentBusiness) }}" target="_blank"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View {{ $currentBusiness->isProductStore() ? 'Store' : 'Business' }}
                        </a>
                    @endif
                </div>
                <div class="border-t border-gray-200 px-3 py-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile more bottom sheet. --}}
    <div x-show="moreSheet" class="fixed inset-0 z-50 lg:hidden" x-cloak>
        <div x-show="moreSheet" @click="moreSheet = false" class="fixed inset-0 bg-black/40" x-transition.opacity></div>
        <div x-show="moreSheet" class="fixed bottom-0 left-0 right-0 z-50 rounded-t-2xl border-t border-gray-200 bg-white shadow-2xl"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-y-full" x-transition:enter-end="translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0" x-transition:leave-end="translate-y-full">
            <div class="mx-auto mt-2 h-1 w-10 rounded-full bg-gray-300"></div>
            <div class="max-h-[70vh] overflow-y-auto px-4 pb-8 pt-3">
                <p class="px-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Plan & Billing</p>
                <div class="mt-2 mb-4 space-y-1">
                    <a href="{{ route('dashboard.subscription') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.subscription*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Subscription
                    </a>
                    <a href="{{ route('dashboard.billing') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.billing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Billing
                    </a>
                </div>

                <p class="px-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Marketing</p>
                <div class="mt-2 mb-4 space-y-1">
                    <a href="{{ route('dashboard.marketing') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.marketing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                        Marketing
                    </a>
                    <a href="{{ route('dashboard.promotions') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.promotions*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                        Promotions
                    </a>
                </div>

                <p class="px-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Other</p>
                <div class="mt-2 mb-4 space-y-1">
                    <a href="{{ route('dashboard.theme') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.theme*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                        Themes
                    </a>
                    <a href="{{ route('dashboard.notifications') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.notifications*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Notifications
                    </a>
                    <a href="{{ route('dashboard.analytics') }}" @click="moreSheet = false"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.analytics*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Analytics
                    </a>
                    @if ($currentBusiness)
                        <a href="{{ route('business.profile', $currentBusiness) }}" target="_blank" @click="moreSheet = false"
                            class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View {{ $currentBusiness->isProductStore() ? 'Store' : 'Business' }}
                        </a>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-6">
                    @csrf
                    <button type="submit" class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Top bar. --}}
    <header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 backdrop-blur-sm">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2.5 sm:px-6 lg:px-8">
            <div class="flex items-center gap-3">
                <button @click="drawerOpen = true" class="rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 lg:hidden">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="/" class="text-lg font-bold tracking-tight text-gray-900 lg:text-xl">Ilthres</a>

                @if ($businesses->count() > 0)
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-1.5 rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50 sm:text-sm">
                            <span class="max-w-24 truncate sm:max-w-32">{{ $currentBusiness?->name ?? 'Select business' }}</span>
                            <svg class="h-3.5 w-3.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" class="absolute left-0 z-50 mt-1 w-56 rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                            @foreach ($businesses as $biz)
                                <a href="{{ route('dashboard.switch', $biz) }}"
                                    class="block px-4 py-2 text-sm {{ $biz->id === $currentBusiness?->id ? 'bg-gray-100 font-medium text-gray-900' : 'text-gray-700 hover:bg-gray-50' }}">
                                    <span class="block truncate">{{ $biz->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $biz->isProductStore() ? 'Store' : 'Service' }}</span>
                                </a>
                            @endforeach
                            <hr class="my-1 border-gray-100">
                            <a href="{{ route('business.create') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50">+ New business</a>
                        </div>
                    </div>
                @elseif ($currentBusiness)
                    <span class="rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-600">
                        {{ $currentBusiness->isProductStore() ? 'Store' : 'Service' }}
                    </span>
                @endif
            </div>

            <div class="flex items-center gap-2 sm:gap-4">
                @livewire(\App\Domains\Notifications\Livewire\NotificationBell::class)
                <span class="hidden text-sm text-gray-500 sm:block">{{ $user->name }}</span>
                <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                </form>
            </div>
        </nav>
    </header>

    {{-- Desktop sidebar + content. --}}
    <div class="mx-auto flex max-w-7xl gap-6 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
        <nav class="hidden w-56 shrink-0 lg:block">
            <div class="sticky top-20 max-h-[calc(100vh-5rem)] space-y-1 overflow-y-auto">
                <p class="px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Main</p>
                <a href="{{ route('dashboard') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('dashboard.business.edit') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.business*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Business
                </a>
                @if ($currentBusiness?->isProductStore())
                    <a href="{{ route('dashboard.products') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.products*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        Products
                    </a>
                @elseif ($currentBusiness?->isServiceBusiness())
                    <a href="{{ route('dashboard.services') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.services*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        Services
                    </a>
                @endif
                <a href="{{ route('dashboard.media') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.media') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Media
                </a>
                <a href="{{ route('dashboard.promotions') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.promotions*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                    Promotions
                </a>
                <a href="{{ route('dashboard.theme') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.theme*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Themes
                </a>

                <p class="mt-6 px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Insights</p>
                <a href="{{ route('dashboard.analytics') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.analytics*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Analytics
                </a>

                <p class="mt-6 px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Plan & Billing</p>
                <a href="{{ route('dashboard.subscription') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.subscription*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Subscription
                </a>
                <a href="{{ route('dashboard.billing') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.billing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Billing
                </a>

                <p class="mt-6 px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Marketing</p>
                <a href="{{ route('dashboard.marketing') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.marketing*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Marketing
                </a>

                <p class="mt-6 px-3 pb-1 text-xs font-semibold uppercase tracking-wider text-gray-400">Other</p>
                <a href="{{ route('dashboard.notifications') }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium {{ request()->routeIs('dashboard.notifications*') ? 'sidebar-active' : 'sidebar-inactive' }}">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Notifications
                </a>

                @if ($currentBusiness)
                    <hr class="my-4 border-gray-200">
                    <a href="{{ route('business.profile', $currentBusiness) }}" target="_blank"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        View {{ $currentBusiness->isProductStore() ? 'Store' : 'Business' }}
                    </a>
                @endif
            </div>
        </nav>

        {{-- Main content area. --}}
        <main class="min-w-0 flex-1 pb-20 lg:pb-0">
            @yield('content')
        </main>
    </div>

    {{-- Mobile bottom tab bar. --}}
    @if ($currentBusiness)
    <nav class="fixed bottom-0 left-0 right-0 z-40 border-t border-gray-200 bg-white/95 backdrop-blur-sm lg:hidden">
        <div class="mx-auto flex max-w-lg items-center justify-around px-2 py-1">
            <a href="{{ route('dashboard') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium {{ request()->routeIs('dashboard') ? 'tab-active' : 'tab-inactive' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
            <a href="{{ route('dashboard.business.edit') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium {{ request()->routeIs('dashboard.business*') ? 'tab-active' : 'tab-inactive' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span>Business</span>
            </a>
            @if ($currentBusiness->isProductStore())
                <a href="{{ route('dashboard.products') }}"
                    class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium {{ request()->routeIs('dashboard.products*') ? 'tab-active' : 'tab-inactive' }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <span>Products</span>
                </a>
            @else
                <a href="{{ route('dashboard.services') }}"
                    class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium {{ request()->routeIs('dashboard.services*') ? 'tab-active' : 'tab-inactive' }}">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span>Services</span>
                </a>
            @endif
            <a href="{{ route('dashboard.media') }}"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium {{ request()->routeIs('dashboard.media') ? 'tab-active' : 'tab-inactive' }}">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Media</span>
            </a>
            <button @click="moreSheet = true"
                class="flex flex-col items-center gap-0.5 px-3 py-1.5 text-xs font-medium text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/></svg>
                <span>More</span>
            </button>
        </div>
    </nav>
    @endif

    @livewireScripts
    <script>
        function dashboardLayout() {
            return {
                drawerOpen: false,
                moreSheet: false,
                init() {
                    document.addEventListener('livewire:navigated', () => {
                        this.drawerOpen = false;
                        this.moreSheet = false;
                    });
                }
            };
        }
    </script>
</body>
</html>
