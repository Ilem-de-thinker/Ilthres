@extends('layouts.dashboard')

@section('title', 'New service')

@section('content')
<div class="max-w-xl">
    <div class="mb-6">
        <a href="{{ route('dashboard.services') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Services</a>
        <h1 class="mt-2 text-2xl font-bold tracking-tight text-gray-900">New service</h1>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-6">
        @livewire(\App\Domains\Service\Livewire\ServiceForm::class, key('service-create'))
    </div>
</div>
@endsection
