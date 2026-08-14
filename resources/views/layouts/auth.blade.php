<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Ilthres Market') }} — @yield('title')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    <div class="flex min-h-screen flex-col lg:flex-row">
        <div class="hidden lg:flex lg:w-1/2 items-center justify-center bg-gradient-to-br from-dark-gold-50 to-dark-gold-100 p-12 relative overflow-hidden">
            <div class="pointer-events-none absolute inset-0">
                <div class="absolute -left-20 -top-20 h-64 w-64 rounded-full bg-dark-gold/10 blur-3xl"></div>
                <div class="absolute -bottom-20 -right-20 h-80 w-80 rounded-full bg-dark-gold/5 blur-3xl"></div>
            </div>
            <div class="relative text-center max-w-md">
                <a href="/" class="text-3xl font-bold tracking-tight text-dark-gold">Ilthres</a>
                <p class="mt-4 text-lg text-gray-700">Your business operating system — sell, book, and grow.</p>
                <div class="mt-8 space-y-4 text-left">
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-600">Professional online presence with custom themes</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-600">Sell products, take bookings, and manage orders</span>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-sm text-gray-600">Get discovered by customers searching nearby</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex w-full lg:w-1/2 items-center justify-center px-4 py-12 sm:px-8">
            <div class="w-full max-w-md">
                <a href="/" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-dark-gold transition lg:hidden mb-8">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to home
                </a>

                @yield('auth-content')
            </div>
        </div>
    </div>

    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Ilthres Market. All rights reserved.</p>
            <div class="flex items-center gap-4">
                <a href="/" class="text-xs text-gray-500 hover:text-dark-gold transition">Home</a>
                <a href="{{ route('terms') }}" class="text-xs text-gray-500 hover:text-dark-gold transition">Terms</a>
                <a href="{{ route('privacy') }}" class="text-xs text-gray-500 hover:text-dark-gold transition">Privacy</a>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
