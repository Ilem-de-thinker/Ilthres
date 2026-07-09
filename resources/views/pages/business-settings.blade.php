{{--
  Business settings page — wraps the Business\BusinessSettings Livewire component.
  Component handles business selection internally.
--}}
@extends('layouts.dashboard')

@section('title', 'Business settings')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Business settings</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your business profile, logo, and theme.</p>
        </div>
        <a href="{{ route('dashboard.business.edit') }}"
            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Edit details
        </a>
    </div>

    @livewire(\App\Domains\Business\Livewire\BusinessSettings::class)
</div>
@endsection
