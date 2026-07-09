@extends('layouts.dashboard')

@section('title', 'New product')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard.products') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Products</a>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">New product</h1>
    </div>

    @livewire(\App\Domains\Product\Livewire\ProductCreate::class)
</div>
@endsection
