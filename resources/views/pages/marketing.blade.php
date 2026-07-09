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

    @livewire(\App\Domains\Marketing\Livewire\LinkStats::class)
    @livewire(\App\Domains\Marketing\Livewire\MarketingLinks::class)
</div>
@endsection
