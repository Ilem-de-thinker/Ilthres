@extends('layouts.admin')

@section('title', 'KYC Verifications')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">KYC Verifications</h1>
            <p class="mt-1 text-sm text-gray-500">Review and approve business verification documents.</p>
        </div>
    </div>

    @livewire(\App\Domains\Administration\Livewire\VerificationManager::class)
</div>
@endsection
