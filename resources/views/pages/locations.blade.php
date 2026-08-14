@extends('layouts.dashboard')

@section('title', 'Locations')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Locations</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your business branches and contact information.</p>
        </div>
    </div>

    @livewire(\App\Domains\Business\Livewire\LocationManager::class)
</div>
@endsection
