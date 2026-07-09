@extends('layouts.dashboard')

@section('title', 'Promotions')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Promotions</h1>
        <p class="mt-1 text-sm text-gray-500">Create and manage promotional campaigns for your products and services.</p>
    </div>

    @livewire(\App\Domains\Promotion\Livewire\PromotionList::class)
</div>
@endsection
