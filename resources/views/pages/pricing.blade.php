@extends('layouts.dashboard')

@section('title', 'Pricing')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Pricing</h1>
        <p class="mt-1 text-sm text-gray-500">Choose the plan that fits your business. Upgrade anytime.</p>
    </div>

    @livewire(\App\Domains\Subscription\Livewire\PricingPage::class)
</div>
@endsection
