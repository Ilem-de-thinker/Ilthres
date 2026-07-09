@extends('layouts.app')

@section('title', 'Digital Business Presence Platform')

@section('content')
<section class="relative overflow-hidden bg-gradient-to-b from-dark-gold-50/30 to-white px-4 pb-20 pt-16 sm:px-6 lg:px-8">
    <div class="animate-fade-in-up mx-auto max-w-7xl">
        <div class="mx-auto max-w-3xl text-center">
            <span class="inline-flex animate-scale-in items-center gap-2 rounded-full border border-dark-gold/20 bg-dark-gold/10 px-3 py-1 text-xs font-medium text-dark-gold">
                <span class="h-1.5 w-1.5 rounded-full bg-dark-gold"></span>
                Launching soon — join the waitlist
            </span>
            <h1 class="mt-6 text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                Your business,<br>
                <span class="text-dark-gold">online in minutes.</span>
            </h1>
            <p class="mt-4 animate-fade-in text-lg text-gray-600" style="animation-delay: 0.15s">
                Create a professional business profile, showcase your products or services,
                and get discovered by customers. No coding required.
            </p>
            <div class="mt-8 flex animate-fade-in-up items-center justify-center gap-4" style="animation-delay: 0.25s">
                <a href="{{ route('register') }}"
                    class="rounded-lg bg-dark-gold px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-dark-gold/30 transition hover:bg-dark-gold-500">
                    Get started free
                </a>
                <a href="{{ route('explore') }}"
                    class="rounded-lg border border-gray-300 bg-white/80 px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm backdrop-blur-sm transition hover:bg-gray-50">
                    Explore businesses
                </a>
            </div>
            <p class="mt-4 animate-fade-in text-sm text-gray-500" style="animation-delay: 0.35s">Free plan available. No credit card required.</p>
        </div>

        <div class="mx-auto mt-16 max-w-5xl animate-scale-in" style="animation-delay: 0.35s">
            <div class="aspect-video rounded-xl border border-gray-200/80 bg-white/60 p-1 shadow-2xl shadow-gray-200/60 backdrop-blur-xl">
                <div class="flex h-full items-center justify-center rounded-lg bg-gradient-to-br from-dark-gold-50/20 to-white">
                    <div class="text-center">
                        <div class="mx-auto flex h-16 w-16 animate-float items-center justify-center rounded-full bg-dark-gold/10">
                            <svg class="h-8 w-8 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
                            </svg>
                        </div>
                        <p class="mt-4 text-sm text-gray-400">Business dashboard preview</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Everything you need to grow</h2>
            <p class="mt-2 text-lg text-gray-600">
                A complete toolkit to build, manage, and promote your business online.
            </p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $features = [
                    ['svg' => 'M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z', 'title' => 'Business profile', 'desc' => 'Create a stunning public profile with your logo, cover image, contact info, and social links.'],
                    ['svg' => 'M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.146 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42', 'title' => 'Products & services', 'desc' => 'List your products with images, variants, and pricing — or showcase your services with descriptions.'],
                    ['svg' => 'M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.992 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182', 'title' => 'Theme customization', 'desc' => 'Personalise your public page with custom colours, fonts, and layouts. Premium themes available.'],
                    ['svg' => 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z', 'title' => 'Discoverability', 'desc' => 'Get found by customers browsing categories, searching keywords, or filtering by location.'],
                    ['svg' => 'M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75', 'title' => 'Analytics', 'desc' => 'Track profile views, visitor trends, and popular items. Understand your audience.'],
                    ['svg' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z', 'title' => 'Marketing tools', 'desc' => 'Generate shareable links and QR codes for your business. Track click-through rates.']
                ];
            @endphp

            @foreach ($features as $i => $f)
                <div class="animate-fade-in-up group rounded-2xl border border-gray-200/80 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/30 hover:shadow-[-4px_-4px_10px_rgba(255,255,255,0.9),4px_4px_20px_rgba(0,0,0,0.06)]" style="animation-delay: {{ 0.06 * $i }}s;">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-dark-gold/10 text-dark-gold shadow-[inset_0_1px_0_0_rgba(255,255,255,0.6)]">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $f['svg'] }}" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $f['title'] }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-gray-50 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">How it works</h2>
            <p class="mt-2 text-lg text-gray-600">Get your business online in three simple steps.</p>
        </div>

        <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
            @php
                $steps = [
                    ['title' => 'Create your account', 'desc' => 'Sign up free and choose your business type — product store or service business.'],
                    ['title' => 'Set up your profile', 'desc' => 'Add your business details, upload media, and customise your theme to match your brand.'],
                    ['title' => 'Get discovered', 'desc' => 'Your profile goes live on the marketplace. Customers find you through search and categories.']
                ];
            @endphp

            @foreach ($steps as $i => $s)
                <div class="animate-fade-in-up text-center" style="animation-delay: {{ 0.1 * $i }}s;">
                    <span class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-dark-gold text-lg font-bold text-white shadow-sm shadow-dark-gold/30">{{ $i + 1 }}</span>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">{{ $s['title'] }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ $s['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center animate-fade-in-up">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Browse by category</h2>
            <p class="mt-2 text-lg text-gray-600">Discover businesses across a wide range of categories.</p>
        </div>

        @php
            $cats = [
                ['icon' => '👗', 'label' => 'Fashion'],
                ['icon' => '🍽️', 'label' => 'Food'],
                ['icon' => '💻', 'label' => 'Electronics'],
                ['icon' => '💄', 'label' => 'Beauty'],
                ['icon' => '🏠', 'label' => 'Home'],
                ['icon' => '💪', 'label' => 'Fitness']
            ];
        @endphp

        <div class="mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($cats as $i => $c)
                <a href="{{ route('explore') }}"
                    class="animate-scale-in group rounded-2xl border border-gray-200 bg-white/70 p-6 text-center shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/30 hover:shadow-[-4px_-4px_10px_rgba(255,255,255,0.9),4px_4px_20px_rgba(0,0,0,0.06)]"
                    style="animation-delay: {{ 0.06 * $i }}s;">
                    <span class="inline-block text-3xl transition-transform duration-300 group-hover:scale-110">{{ $c['icon'] }}</span>
                    <p class="mt-2 text-sm font-medium text-gray-600 transition-colors group-hover:text-dark-gold">{{ $c['label'] }}</p>
                </a>
            @endforeach
        </div>

        <div class="mt-8 text-center animate-fade-in">
            <a href="{{ route('explore') }}" class="text-sm font-medium text-dark-gold transition hover:text-dark-gold-500">View all categories &rarr;</a>
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
                        One business profile
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Basic theme
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Limited analytics
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
                        Advanced analytics
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Priority search placement
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        50 media uploads
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Marketing links
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
                        Multiple staff accounts
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Custom domain
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4 shrink-0 text-dark-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Priority support
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
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">Trusted by businesses</h2>
            <p class="mt-2 text-lg text-gray-600">See what merchants are saying about Ilthres Market.</p>
        </div>

        @php
            $testimonials = [
                ['text' => 'I had my fashion store online in under 10 minutes. The setup process is incredibly smooth.', 'initials' => 'AO', 'name' => 'Adeola O.', 'role' => 'Fashion retailer'],
                ['text' => 'The analytics alone are worth it. I can see exactly which services customers are interested in.', 'initials' => 'CI', 'name' => 'Chidi I.', 'role' => 'Salon owner'],
                ['text' => 'Premium theme made my clinic look professional. Customers often comment on how clean the page is.', 'initials' => 'FO', 'name' => 'Dr. Fatima O.', 'role' => 'Medical clinic']
            ];
        @endphp

        <div class="mt-12 grid grid-cols-1 gap-8 md:grid-cols-3">
            @foreach ($testimonials as $i => $t)
                <div class="animate-fade-in-up rounded-2xl border border-gray-200 bg-white/70 p-8 shadow-[-4px_-4px_10px_rgba(255,255,255,0.8),4px_4px_16px_rgba(0,0,0,0.04)] backdrop-blur-xl transition-all duration-300 hover:border-dark-gold/20" style="animation-delay: {{ 0.08 * $i }}s;">
                    <p class="text-sm text-gray-600">"{{ $t['text'] }}"</p>
                    <footer class="mt-4 flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-dark-gold/10 text-sm font-medium text-dark-gold">{{ $t['initials'] }}</div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $t['name'] }}</p>
                            <p class="text-xs text-gray-500">{{ $t['role'] }}</p>
                        </div>
                    </footer>
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
                ['q' => 'What is Ilthres Market?', 'a' => 'Ilthres Market is a digital business presence platform that lets merchants create professional online profiles, showcase products or services, and get discovered by customers.'],
                ['q' => 'Is it really free?', 'a' => 'Yes. The Free plan is always available with core features. You can upgrade to Professional or Business whenever you need more.'],
                ['q' => 'Can I switch my business type later?', 'a' => 'The business type is set at creation and determines which features are available. You can create multiple businesses with different types under one account.'],
                ['q' => 'What payment methods do you accept?', 'a' => 'We accept major Nigerian payment methods including Paystack, Flutterwave, and bank transfers for annual plans.'],
                ['q' => 'How do customers find my business?', 'a' => 'Your profile appears in the Ilthres Market directory. Customers can search by keyword, browse categories, or filter by location. SEO optimisation is included on all plans.']
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
    <div class="mx-auto max-w-3xl text-center animate-fade-in-up">
        <h2 class="text-3xl font-bold tracking-tight text-gray-900">Ready to grow your business?</h2>
        <p class="mt-2 text-lg text-gray-600">Join thousands of merchants already using Ilthres Market. Create your profile free.</p>
        <div class="mt-8 flex items-center justify-center gap-4">
            <a href="{{ route('register') }}"
                class="rounded-lg bg-dark-gold px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-dark-gold/30 transition hover:bg-dark-gold-500">
                Get started free
            </a>
            <a href="{{ route('explore') }}"
                class="rounded-lg border border-gray-300 bg-white px-6 py-3 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50">
                Browse marketplace
            </a>
        </div>
    </div>
</section>
@endsection
