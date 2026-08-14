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

<section class="border-t border-gray-200 bg-gray-50 px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="mx-auto max-w-2xl text-center">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Ready to list your business?</h2>
            <p class="mt-2 text-gray-600">Join the discovery platform and reach more customers.</p>
            <div class="mt-6">
                <a href="{{ route('register') }}" class="rounded-lg bg-dark-gold px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-dark-gold/30 transition hover:bg-dark-gold-500">
                    Get started free
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
