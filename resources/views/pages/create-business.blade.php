{{--
  Create business page — wraps the Business\CreateBusiness Livewire component.
  Used for adding additional businesses (multi-business users).
--}}
@extends('layouts.dashboard')

@section('title', 'Create business')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Create your business</h1>
        <p class="mt-1 text-sm text-gray-500">Set up your business profile to start selling products or offering services.</p>
    </div>

    <div class="rounded-lg border border-gray-200 bg-white p-8">
        @livewire(\App\Domains\Business\Livewire\CreateBusiness::class)
    </div>
</div>
@endsection
