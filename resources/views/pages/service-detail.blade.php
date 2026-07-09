@extends('layouts.dashboard')

@section('title', $service->name)

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard.services') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Services</a>
    </div>

    @livewire(\App\Domains\Service\Livewire\ServiceDetail::class, ['serviceId' => $service->id])
</div>
@endsection
