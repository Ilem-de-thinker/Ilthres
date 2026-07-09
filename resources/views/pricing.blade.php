{{--
  Pricing page — lists all subscription plans with feature comparison.
  Will be replaced by Subscription\PricingPage Livewire component in Phase 1 Feature 10.
--}}
@extends('layouts.app')

@section('title', 'Pricing')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-2xl text-center">
        <h1 class="text-4xl font-bold tracking-tight text-gray-900">Pricing</h1>
        <p class="mt-2 text-lg text-gray-600">Choose the plan that fits your business. Upgrade anytime.</p>
    </div>

    <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-3">
        {{-- Free plan --}}
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h3 class="text-lg font-semibold text-gray-900">Free</h3>
            <p class="mt-2 text-sm text-gray-500">Perfect for getting started</p>
            <p class="mt-4">
                <span class="text-4xl font-bold text-gray-900">₦0</span>
                <span class="text-sm text-gray-500">/month</span>
            </p>
            <ul class="mt-6 space-y-3">
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> One business profile</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Basic theme</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Limited analytics</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 5 media uploads</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Get started</a>
        </div>

        {{-- Professional plan --}}
        <div class="rounded-xl border-2 border-gray-900 bg-white p-8 shadow-lg">
            <h3 class="text-lg font-semibold text-gray-900">Professional</h3>
            <p class="mt-2 text-sm text-gray-500">For growing businesses</p>
            <p class="mt-4">
                <span class="text-4xl font-bold text-gray-900">₦15,000</span>
                <span class="text-sm text-gray-500">/month</span>
            </p>
            <ul class="mt-6 space-y-3">
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Premium themes</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Advanced analytics</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority search placement</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> 50 media uploads</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Marketing links</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 flex w-full items-center justify-center rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Start free trial</a>
        </div>

        {{-- Business plan --}}
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h3 class="text-lg font-semibold text-gray-900">Business</h3>
            <p class="mt-2 text-sm text-gray-500">For established businesses</p>
            <p class="mt-4">
                <span class="text-4xl font-bold text-gray-900">₦35,000</span>
                <span class="text-sm text-gray-500">/month</span>
            </p>
            <ul class="mt-6 space-y-3">
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Everything in Professional</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Multiple staff accounts</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Custom domain</li>
                <li class="flex items-center gap-2 text-sm text-gray-600"><svg class="h-4 w-4 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Priority support</li>
            </ul>
            <a href="{{ route('register') }}" class="mt-8 flex w-full items-center justify-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Contact sales</a>
        </div>
    </div>
</div>
@endsection
