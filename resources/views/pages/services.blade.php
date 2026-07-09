@extends('layouts.dashboard')

@section('title', 'Services')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Services</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your service catalogue, pricing, and availability.</p>
    </div>

    @livewire(\App\Domains\Service\Livewire\ServiceList::class)
</div>
@endsection
