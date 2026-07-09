@extends('layouts.app')

@section('title', 'Explore businesses')

@section('content')
<div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
    <div class="text-center">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">Discover businesses</h1>
        <p class="mt-2 text-lg text-gray-600">Find local businesses, products, and services near you.</p>
    </div>

    <div class="mt-8">
        @livewire(\App\Domains\Search\Livewire\ExploreSearch::class)
    </div>
</div>
@endsection
