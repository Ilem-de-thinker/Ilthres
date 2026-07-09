@extends('layouts.dashboard')

@section('title', $product->name)

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('dashboard.products') }}" class="text-sm text-gray-500 hover:text-gray-900">&larr; Products</a>
    </div>

    @livewire(\App\Domains\Product\Livewire\ProductDetail::class, ['productId' => $product->id])
</div>
@endsection
