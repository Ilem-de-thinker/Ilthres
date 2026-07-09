<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ilthres Market') }} — Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">

    {{-- Admin top bar. --}}
    <header class="sticky top-0 z-50 border-b border-gray-200 bg-white">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <a href="/admin" class="text-lg font-bold tracking-tight text-gray-900">Ilthres Admin</a>
                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">Admin</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="/dashboard" class="text-sm text-gray-600 hover:text-gray-900">Dashboard</a>
                <span class="text-sm text-gray-500">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">Logout</button>
                </form>
            </div>
        </nav>
    </header>

    <div class="mx-auto flex max-w-7xl gap-8 px-4 py-8 sm:px-6 lg:px-8">
        {{-- Admin sidebar. --}}
        <nav class="hidden w-56 shrink-0 lg:block">
            <div class="flex flex-col gap-1">
                <a href="{{ route('admin.dashboard') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.users') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.users') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Users
                </a>
                <a href="{{ route('admin.businesses') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.businesses') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Businesses
                </a>
                <a href="{{ route('admin.payments') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.payments') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Payments
                </a>
                <a href="{{ route('admin.subscriptions') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.subscriptions') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Subscriptions
                </a>
                <a href="{{ route('admin.themes') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.themes') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Themes
                </a>
                <a href="{{ route('admin.categories') }}"
                    class="rounded-lg px-3 py-2 text-sm font-medium {{ request()->routeIs('admin.categories') ? 'bg-gray-100 text-gray-900' : 'text-gray-700 hover:bg-gray-100' }}">
                    Categories
                </a>
            </div>
        </nav>

        {{-- Admin main content. --}}
        <main class="min-w-0 flex-1">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 border border-green-200">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                    {{ session('error') }}
                </div>
            @endif
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>
</html>
