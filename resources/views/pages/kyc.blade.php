@extends('layouts.dashboard')

@section('title', 'Verification')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">KYC Verification</h1>
            <p class="mt-1 text-sm text-gray-500">Verify your business identity to unlock the verified badge and build trust with customers.</p>
        </div>
    </div>

    @livewire(\App\Domains\Business\Livewire\KycSettings::class)
</div>
@endsection
