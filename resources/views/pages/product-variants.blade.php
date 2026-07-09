@extends('layouts.dashboard')

@section('title', 'Variants - ' . $product->name)

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard.products.show', $product) }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back</a>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Variants</h1>
        <p class="text-sm text-gray-500">{{ $product->name }}</p>
    </div>

    @livewire(\App\Domains\Product\Livewire\ProductVariants::class, ['productId' => $product->id])
</div>
@endsection
