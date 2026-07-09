@extends('layouts.dashboard')

@section('title', 'Edit ' . $product->name)

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard.products.show', $product) }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Back</a>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit product</h1>
    </div>

    @livewire(\App\Domains\Product\Livewire\ProductEdit::class, ['productId' => $product->id])
</div>
@endsection
