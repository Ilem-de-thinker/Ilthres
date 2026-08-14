@extends('layouts.app')

@section('title', 'Discover Any Business. Grow Every Business.')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-dark-gold-50/30 to-white px-4 pb-20 pt-16 sm:px-6 lg:px-8">
    <div class="animate-fade-in-up mx-auto max-w-7xl">
        <div class="mx-auto max-w-3xl text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                Discover Any Business.<br>
                <span class="text-dark-gold">Grow Every Business.</span>
            </h1>
            <p class="mt-5 text-lg text-gray-600 max-w-xl mx-auto leading-relaxed">
                Find businesses, services, products, and professionals — or create your own professional business presence in minutes.
            </p>

            <div class="mt-10 mx-auto max-w-2xl" x-data="{ focused: false, query: '' }">
                <div class="flex items-center rounded-full border-2 border-gray-200 bg-white shadow-lg shadow-gray-100/80 transition-all" :class="focused ? 'border-dark-gold shadow-xl shadow-dark-gold-100/30' : ''">
                    <div class="pl-5 pr-2 text-gray-400">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    </div>
                    <input x-model="query" @focus="focused = true" @blur="setTimeout(() => focused = false, 200)" type="text" placeholder="Search businesses, services, products or ask anything..." class="flex-1 py-4 px-2 text-base text-gray-900 placeholder:text-gray-400 bg-transparent outline-none rounded-l-full">
                    <a href="{{ route('explore') }}" class="mr-2 rounded-full bg-dark-gold px-6 py-2.5 text-sm font-medium text-white hover:bg-dark-gold-500 transition-colors">
                        Search
                    </a>
                </div>
                <div x-show="focused && query.length === 0" x-cloak x-transition class="absolute top-full left-0 right-0 mt-2 rounded-2xl border border-gray-200 bg-white shadow-xl p-4 z-10">
                    <p class="text-xs font-medium text-gray-400 uppercase tracking-wider px-2 mb-3">Popular searches</p>
                    <div class="space-y-1">
                        <a href="{{ route('explore') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <span class="text-sm text-gray-700">Restaurant near me</span>
                        </a>
                        <a href="{{ route('explore') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                            <span class="text-sm text-gray-700">Wedding photographer</span>
                        </a>
                        <a href="{{ route('explore') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m0 0l3-3m-3 3l3-3m5.25-3.75L19.5 4.5a2.121 2.121 0 013 3l-5.25 5.25"/></svg>
                            <span class="text-sm text-gray-700">Mechanic open now</span>
                        </a>
                        <a href="{{ route('explore') }}" class="flex items-center gap-3 rounded-xl px-3 py-2.5 hover:bg-gray-50 transition-colors">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                            <span class="text-sm text-gray-700">Lawyer</span>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                <span class="text-xs text-gray-400">Trending:</span>
                <a href="{{ route('explore') }}" class="rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">Restaurants</a>
                <a href="{{ route('explore') }}" class="rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">Hotels</a>
                <a href="{{ route('explore') }}" class="rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">Medical</a>
                <a href="{{ route('explore') }}" class="rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">Fashion</a>
                <a href="{{ route('explore') }}" class="rounded-full bg-white border border-gray-200 px-3 py-1 text-xs font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 transition-colors">Technology</a>
            </div>

            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}"
                    class="rounded-lg bg-dark-gold px-8 py-3 text-sm font-semibold text-white shadow-sm shadow-dark-gold/30 transition hover:bg-dark-gold-500 w-full sm:w-auto text-center">
                    Get Started Free
                </a>
                <a href="{{ route('explore') }}"
                    class="rounded-lg border border-gray-300 bg-white/80 px-8 py-3 text-sm font-semibold text-gray-700 shadow-sm backdrop-blur-sm transition hover:bg-gray-50 w-full sm:w-auto text-center">
                    Explore Businesses
                </a>
            </div>
            <p class="mt-4 animate-fade-in text-sm text-gray-500" style="animation-delay: 0.35s">Create your professional website in minutes. No credit card required.</p>
        </div>

        <div class="mx-auto mt-16 max-w-5xl animate-scale-in" style="animation-delay: 0.35s">
            <div class="rounded-xl border border-gray-200/80 bg-white/60 p-1 shadow-2xl shadow-gray-200/60 backdrop-blur-xl">
                <img src="{{ asset('dashboard image.PNG') }}" alt="OmniBusiness dashboard preview" class="w-full rounded-lg object-cover">
            </div>
        </div>
    </div>
</section>

<section class="py-14 border-b border-gray-200/60">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <p class="text-center text-xs font-medium text-gray-400 uppercase tracking-wider">Trusted by forward-thinking businesses</p>
        <div class="mt-6 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 opacity-30">
            <span class="text-lg font-bold tracking-tight">Lagos Foods</span>
            <span class="text-lg font-bold tracking-tight">Abuja Hotels</span>
            <span class="text-lg font-bold tracking-tight">Naija Fashion</span>
            <span class="text-lg font-bold tracking-tight">TechHub</span>
            <span class="text-lg font-bold tracking-tight">GreenLeaf</span>
        </div>
    </div>
</section>

<section id="features" class="px-4 py-20 sm:px-6 lg:px-8 bg-gray-50">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <p class="text-xs font-semibold uppercase tracking-wider text-dark-gold">Features</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Everything your business needs</h2>
            <p class="mt-3 text-base text-gray-600">One platform to discover, manage, and grow — whether you're a customer or a business owner.</p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">AI-Powered Search</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Find exactly what you need with intelligent search that understands natural language and context.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016A3.001 3.001 0 0021 9.349m-18 0V6a2.25 2.25 0 012.25-2.25h15A2.25 2.25 0 0121 6v3.349"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Business Discovery</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Browse verified businesses with real reviews, photos, and detailed information across every category.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Website Builder</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Create a professional business website in minutes with our theme engine. No coding needed.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Booking System</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Accept appointments and bookings 24/7 with an integrated scheduling system your customers will love.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Product Marketplace</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Sell products directly from your profile. Manage inventory, orders, and shipping from one place.</p>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-8 hover:shadow-lg hover:shadow-gray-100/80 transition-all">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold text-white">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/></svg>
                </div>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Analytics Dashboard</h3>
                <p class="mt-2 text-sm leading-6 text-gray-600">Track visitors, revenue, bookings, and search rankings with beautiful, actionable analytics.</p>
            </div>
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <p class="text-xs font-semibold uppercase tracking-wider text-dark-gold">How it works</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Get started in three steps</h2>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-12 sm:grid-cols-3">
            <div class="text-center animate-fade-in-up">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-dark-gold text-lg font-bold text-white shadow-sm shadow-dark-gold/30">1</span>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Search or create</h3>
                <p class="mt-2 text-sm text-gray-600 leading-6">Find any business or create your professional presence in under 5 minutes.</p>
            </div>
            <div class="text-center animate-fade-in-up" style="animation-delay: 0.1s;">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-dark-gold text-lg font-bold text-white shadow-sm shadow-dark-gold/30">2</span>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Customize your profile</h3>
                <p class="mt-2 text-sm text-gray-600 leading-6">Add products, services, photos, and booking options. Make it uniquely yours.</p>
            </div>
            <div class="text-center animate-fade-in-up" style="animation-delay: 0.2s;">
                <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-dark-gold text-lg font-bold text-white shadow-sm shadow-dark-gold/30">3</span>
                <h3 class="mt-5 text-base font-semibold text-gray-900">Grow your business</h3>
                <p class="mt-2 text-sm text-gray-600 leading-6">Get discovered by customers, accept bookings, sell products, and track your growth.</p>
            </div>
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Two ways to sell</h2>
            <p class="mt-2 text-lg text-gray-600">Whether you sell products or offer services, Ilthres has you covered.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2">
            <div class="animate-fade-in-up rounded-2xl border border-gray-200/80 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/30">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold/10 text-dark-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121 0 2.09-.773 2.34-1.872l1.836-8.046A1.125 1.125 0 0018.054 3H5.106m2.394 11.25l-1.5-6h13.5"/></svg>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Product Store</h3>
                <p class="mt-3 text-sm text-gray-600">Sell physical and digital products with inventory tracking, product variants, and online checkout.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-medium text-dark-gold transition hover:text-dark-gold-500">
                    Create store
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>

            <div class="animate-fade-in-up rounded-2xl border border-gray-200/80 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/30" style="animation-delay: 0.1s;">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold/10 text-dark-gold">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                </div>
                <h3 class="mt-4 text-xl font-semibold text-gray-900">Service Business</h3>
                <p class="mt-3 text-sm text-gray-600">Offer appointments and services with online booking, scheduling, and customer management.</p>
                <a href="{{ route('register') }}" class="mt-6 inline-flex items-center gap-1 text-sm font-medium text-dark-gold transition hover:text-dark-gold-500">
                    Create business
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <p class="text-xs font-semibold uppercase tracking-wider text-dark-gold">Categories</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Explore by category</h2>
            <p class="mt-3 text-base text-gray-600">Discover businesses across every industry</p>
        </div>

        @php
            $cats = [
                ['svg' => 'M12 8.25v-1.5m0 1.5c-1.355 0-2.697.056-4.024.166C6.845 8.51 6 9.473 6 10.608v2.513m6-4.87c1.355 0 2.697.055 4.024.165C17.155 8.51 18 9.473 18 10.608v2.513m-3-4.87v-1.5m-6 1.5v-1.5m12 9.75l-1.5.75a3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0 3.354 3.354 0 00-3 0 3.354 3.354 0 01-3 0L3 16.5m15-3.38a48.474 48.474 0 00-6-.371c-2.032 0-4.034.126-6 .371m12 0c.39.049.777.102 1.163.16 1.07.16 1.837 1.094 1.837 2.175v5.17c0 .62-.504 1.124-1.125 1.124H4.125A1.125 1.125 0 013 20.625v-5.17c0-1.08.768-2.014 1.837-2.174A47.78 47.78 0 016 13.12', 'label' => 'Restaurants', 'color' => 'red'],
                ['svg' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z', 'label' => 'Hotels', 'color' => 'blue'],
                ['svg' => 'M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z', 'label' => 'Medical', 'color' => 'emerald'],
                ['svg' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z', 'label' => 'Fashion', 'color' => 'pink'],
                ['svg' => 'M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5', 'label' => 'Education', 'color' => 'amber'],
                ['svg' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z', 'label' => 'Real Estate', 'color' => 'violet'],
                ['svg' => 'M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5', 'label' => 'Technology', 'color' => 'sky'],
                ['svg' => 'M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z', 'label' => 'Beauty', 'color' => 'rose'],
                ['svg' => 'M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.589-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.589-1.202L5.25 4.971z', 'label' => 'Legal', 'color' => 'slate'],
                ['svg' => 'M11.42 15.17l-5.25 5.25a2.121 2.121 0 01-3-3l5.25-5.25m0 0l3-3m-3 3l3-3m5.25-3.75L19.5 4.5a2.121 2.121 0 013 3l-5.25 5.25', 'label' => 'Construction', 'color' => 'orange'],
                ['svg' => 'M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12', 'label' => 'Automotive', 'color' => 'teal'],
            ];
        @endphp

        <div class="mt-12 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach ($cats as $i => $c)
                <a href="{{ route('explore') }}"
                    class="animate-scale-in group flex flex-col items-center gap-3 rounded-2xl border border-gray-200 bg-white p-6 hover:border-dark-gold/30 hover:shadow-lg hover:shadow-dark-gold-50 transition-all"
                    style="animation-delay: {{ 0.04 * $i }}s;">
                    <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-{{ $c['color'] }}-50 text-{{ $c['color'] }}-500 group-hover:bg-{{ $c['color'] }}-500 group-hover:text-white transition-all">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $c['svg'] }}" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium text-gray-700 group-hover:text-dark-gold transition-colors">{{ $c['label'] }}</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gray-50 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Simple, transparent pricing</h2>
            <p class="mt-2 text-lg text-gray-600">Start free. Upgrade when you need more.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-3">
            <div class="animate-fade-in-up rounded-2xl border border-gray-200 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-gray-300">
                <h3 class="text-lg font-semibold text-gray-900">Free</h3>
                <p class="mt-2 text-sm text-gray-600">Perfect for getting started</p>
                <p class="mt-4">
                    <span class="text-4xl font-bold text-gray-900">₦0</span>
                    <span class="text-sm text-gray-500">/month</span>
                </p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Business profile
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Basic theme
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        5 products or services
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Basic analytics
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        5 media uploads
                    </li>
                </ul>
                <a href="{{ route('register') }}"
                    class="mt-8 flex w-full items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Get started
                </a>
            </div>

            <div class="animate-fade-in-up relative rounded-2xl border border-dark-gold/40 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04),0_0_20px_-8px_rgba(184,150,15,0.2)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/60" style="animation-delay: 0.1s;">
                <span class="absolute -top-3 left-1/2 -translate-x-1/2 inline-block rounded-full bg-dark-gold px-4 py-1 text-xs font-medium text-white">Most popular</span>
                <h3 class="mt-2 text-lg font-semibold text-gray-900">Professional</h3>
                <p class="mt-2 text-sm text-gray-600">For growing businesses</p>
                <p class="mt-4">
                    <span class="text-4xl font-bold text-dark-gold">₦15,000</span>
                    <span class="text-sm text-gray-500">/month</span>
                </p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Premium themes
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Unlimited products
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Booking system
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Marketing tools
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Custom branding
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Advanced analytics
                    </li>
                </ul>
                <a href="{{ route('register') }}"
                    class="mt-8 flex w-full items-center justify-center rounded-xl bg-dark-gold px-4 py-2 text-sm font-semibold text-white shadow-sm shadow-dark-gold/30 transition hover:bg-dark-gold-500">
                    Start free trial
                </a>
            </div>

            <div class="animate-fade-in-up rounded-2xl border border-gray-200 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-gray-300" style="animation-delay: 0.2s;">
                <h3 class="text-lg font-semibold text-gray-900">Business</h3>
                <p class="mt-2 text-sm text-gray-600">For established businesses</p>
                <p class="mt-4">
                    <span class="text-4xl font-bold text-gray-900">₦35,000</span>
                    <span class="text-sm text-gray-500">/month</span>
                </p>
                <ul class="mt-6 space-y-3">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Everything in Professional
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Custom domain
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Multiple staff accounts
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Priority support
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        API access
                    </li>
                </ul>
                <a href="{{ route('register') }}"
                    class="mt-8 flex w-full items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                    Contact sales
                </a>
            </div>
        </div>

        <p class="mt-8 animate-fade-in text-center text-sm text-gray-500">
            All plans include a 14-day free trial on Professional and Business plans.
            <a href="{{ route('register') }}" class="font-medium text-dark-gold hover:text-dark-gold-500">Compare plans</a>
        </p>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <p class="text-xs font-semibold uppercase tracking-wider text-dark-gold">Testimonials</p>
            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Loved by businesses worldwide</h2>
        </div>

        @php
            $testimonials = [
                ['text' => 'We went from having no online presence to a full professional website in 20 minutes. The discovery feature brings us new customers every week.', 'initials' => 'AL', 'name' => 'Amara Liu', 'role' => 'Owner, Bloom Florist', 'color' => 'dark-gold'],
                ['text' => 'The booking system alone replaced two tools we were paying for. Our clients love how easy it is to schedule appointments online.', 'initials' => 'DK', 'name' => 'David Kim', 'role' => 'Founder, Apex Wellness', 'color' => 'emerald'],
                ['text' => 'I use OmniBusiness to find local services all the time. The AI search actually understands what I need. It\'s like having a personal assistant.', 'initials' => 'SP', 'name' => 'Sofia Patel', 'role' => 'Regular customer', 'color' => 'amber'],
            ];
        @endphp

        <div class="mt-16 grid max-w-2xl grid-cols-1 gap-6 lg:max-w-none lg:grid-cols-3">
            @foreach ($testimonials as $i => $t)
                <div class="animate-fade-in-up rounded-2xl border border-gray-200 p-8 transition-all duration-300 hover:shadow-lg" style="animation-delay: {{ 0.08 * $i }}s;">
                    <div class="flex gap-0.5 text-amber-400">
                        @for ($s = 0; $s < 5; $s++)
                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <p class="mt-5 text-sm leading-6 text-gray-600">"{{ $t['text'] }}"</p>
                    <div class="mt-6 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-full bg-{{ $t['color'] }}-100 flex items-center justify-center text-sm font-semibold text-{{ $t['color'] }}-700">{{ $t['initials'] }}</div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $t['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $t['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gray-50 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl">
        <div class="text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Frequently asked questions</h2>
        </div>

        @php
            $faqs = [
                ['q' => 'What is OmniBusiness?', 'a' => 'OmniBusiness is an AI-powered business discovery platform that lets you create a professional online presence, sell products, take bookings, and get discovered by customers — all from one platform.'],
                ['q' => 'Is it really free?', 'a' => 'Yes. The Free plan is always available with core features including a business profile, basic theme, and analytics. You can upgrade to Professional or Business whenever you need more.'],
                ['q' => 'Can I use my own website or domain?', 'a' => 'Yes. The Business plan supports custom domains. You can connect your own domain (e.g., yourbusiness.com) while still managing everything through the OmniBusiness dashboard.'],
                ['q' => 'How do customers find my business?', 'a' => 'Your profile appears in the OmniBusiness discovery platform. Customers can search by keyword, browse categories, or filter by location. AI-powered search ensures relevant results.'],
                ['q' => 'Can I take bookings and orders online?', 'a' => 'Yes. Service businesses can accept online bookings with scheduling. Product stores can process orders with checkout. Both are available on the Professional plan and above.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept major Nigerian payment methods including Paystack, Flutterwave, and bank transfers for annual plans.'],
                ['q' => 'Can I switch between product store and service business?', 'a' => 'You can create multiple businesses with different types under one account. Each business can be either a product store or a service business.']
            ];
        @endphp

        <div class="mt-12 space-y-4 animate-fade-in-up">
            @foreach ($faqs as $faq)
                <details class="group rounded-2xl border border-gray-200 bg-white shadow-sm transition-all duration-300 hover:border-gray-300 [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex cursor-pointer items-center justify-between px-6 py-4 text-sm font-medium text-gray-900 transition">
                        {{ $faq['q'] }}
                        <svg class="h-4 w-4 shrink-0 text-dark-gold transition group-open:rotate-45" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </summary>
                    <p class="border-t border-gray-100 px-6 py-4 text-sm text-gray-600">
                        {{ $faq['a'] }}
                    </p>
                </details>
            @endforeach
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="rounded-2xl bg-dark-gold px-8 py-16 sm:px-16 sm:py-20 text-center">
            <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to grow your business?</h2>
            <p class="mx-auto mt-4 max-w-xl text-base text-dark-gold-100">Join thousands of businesses already on OmniBusiness. Create your professional presence today — it's free.</p>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('register') }}" class="rounded-xl bg-white px-8 py-3 text-sm font-semibold text-dark-gold hover:bg-dark-gold-50 transition-all w-full sm:w-auto text-center">Get Started Free</a>
                <a href="{{ route('explore') }}" class="rounded-xl border border-white/30 px-8 py-3 text-sm font-semibold text-white hover:bg-white/10 transition-all w-full sm:w-auto text-center">Explore Businesses</a>
            </div>
        </div>
    </div>
</section>
@endsection
