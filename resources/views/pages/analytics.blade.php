@extends('layouts.dashboard')

@section('title', 'Analytics')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Analytics</h1>
        <p class="mt-1 text-sm text-gray-500">Track your business performance.</p>
    </div>

    @livewire(\App\Domains\Analytics\Livewire\AnalyticsDashboard::class)
</div>
@endsection
