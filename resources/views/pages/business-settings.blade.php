{{--
  Business settings page — wraps the Business\BusinessSettings Livewire component.
  Component handles business selection internally.
--}}
@extends('layouts.dashboard')

@section('title', 'Business settings')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Business settings</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your business details, theme, and payment settings.</p>
    </div>

    @livewire(\App\Domains\Business\Livewire\BusinessSettings::class)
</div>
@endsection
