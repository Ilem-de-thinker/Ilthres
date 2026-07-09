@extends('layouts.dashboard')

@section('title', 'Subscription')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Subscription</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your plan and billing.</p>
    </div>

    @livewire(\App\Domains\Subscription\Livewire\SubscriptionStatus::class)
</div>
@endsection
