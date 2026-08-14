<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'OmniBusiness') }} — @yield('title', 'Discover Any Business. Grow Every Business.')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-gray-900 antialiased">
    <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/70 backdrop-blur-xl">
        <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
            <a href="/" class="text-xl font-bold tracking-tight text-dark-gold">Ilthres</a>
            <div class="flex items-center gap-6">
                <a href="{{ route('explore') }}" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Explore</a>
                @auth
                    <a href="{{ route('dashboard.pricing') }}" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Pricing</a>
                @else
                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Pricing</a>
                @endauth
                @auth
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-500 transition hover:text-dark-gold">Login</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-dark-gold px-4 py-2 text-sm font-medium text-white shadow-sm transition hover:bg-dark-gold-500">Get Started</a>
                @endauth
            </div>
        </nav>
    </header>

    <main>
        @isset($slot)
            {!! $slot !!}
        @else
            @yield('content')
        @endisset
    </main>

    <footer class="border-t border-gray-200 bg-gray-50">
        <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-8 md:grid-cols-4">
                <div>
                    <h3 class="text-sm font-semibold text-dark-gold">Platform</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('explore') }}" class="text-sm text-gray-600 transition hover:text-dark-gold">Explore</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm text-gray-600 transition hover:text-dark-gold">Pricing</a></li>
                        <li><a href="{{ route('explore') }}" class="text-sm text-gray-600 transition hover:text-dark-gold">Categories</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-dark-gold">Resources</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">Support</a></li>
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">Blog</a></li>
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">Guides</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-dark-gold">Company</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="{{ route('privacy') }}" class="text-sm text-gray-600 transition hover:text-dark-gold">Privacy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-sm text-gray-600 transition hover:text-dark-gold">Terms</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-dark-gold">Connect</h3>
                    <ul class="mt-4 space-y-3">
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">Twitter</a></li>
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">Instagram</a></li>
                        <li><a href="#" class="text-sm text-gray-600 transition hover:text-dark-gold">LinkedIn</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-200 pt-8 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Ilthres Market. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
