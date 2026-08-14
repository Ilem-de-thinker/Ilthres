@extends('layouts.dashboard')

@section('title', 'Marketing')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Marketing</h1>
            <p class="mt-1 text-sm text-gray-500">Create tracking links and monitor campaign performance.</p>
        </div>
        <a href="{{ route('dashboard.marketing.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">New link</a>
    </div>

    {{-- How it works --}}
    <div x-data="{ open: false }" x-cloak class="rounded-lg border border-gray-200 bg-white">
        <button @click="open = !open" type="button" class="flex w-full items-center justify-between px-5 py-3 text-left">
            <span class="text-sm font-medium text-gray-700">How marketing links work</span>
            <svg x-bind:class="open ? 'rotate-180' : ''" class="h-4 w-4 text-gray-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="open" x-collapse class="border-t border-gray-100 px-5 py-4 space-y-3 text-sm text-gray-600">
            <p><strong>1. Create a link per channel</strong> — e.g. "Facebook Sale", "Instagram Promo". Each gets its own short URL like <code class="text-gray-900">127.0.0.1/go/facebook-sale</code>.</p>
            <p><strong>2. Share the short link</strong> — post it on social media, WhatsApp, flyers, etc. All pointing to your store.</p>
            <p><strong>3. Track where traffic comes from</strong> — the dashboard shows clicks per link so you know which channel drives the most visitors.</p>
            <p class="text-xs text-gray-400">Example: Create "Facebook" and "Instagram" links → share each on its platform → compare clicks to see which works best.</p>
        </div>
    </div>

    @livewire(\App\Domains\Marketing\Livewire\LinkStats::class)
    @livewire(\App\Domains\Marketing\Livewire\MarketingLinks::class)
</div>
@endsection
