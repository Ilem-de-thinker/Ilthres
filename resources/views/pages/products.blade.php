@extends('layouts.dashboard')

@section('title', 'Products')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Products</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your product catalogue, pricing, and inventory.</p>
    </div>

    @livewire(\App\Domains\Product\Livewire\ProductList::class)
</div>
@endsection
