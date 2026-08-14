@extends('layouts.dashboard')

@section('title', 'Hero Banners')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Hero Banners</h1>
            <p class="mt-1 text-sm text-gray-500">Manage the hero carousel on your public page.</p>
        </div>
    </div>

    @livewire(\App\Domains\Marketing\Livewire\PromoBannerEditor::class)
</div>
@endsection
