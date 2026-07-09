@extends('layouts.dashboard')

@section('title', 'New Link')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Create marketing link</h1>
        <p class="mt-1 text-sm text-gray-500">Create a short tracking link to monitor campaign performance.</p>
    </div>

    @livewire(\App\Domains\Marketing\Livewire\CreateLink::class)
</div>
@endsection
