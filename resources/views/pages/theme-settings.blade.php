{{--
  Theme settings page — wraps the ThemeCustomizer Livewire component.
  Business owners can browse, activate, and customise themes here.
--}}
@extends('layouts.dashboard')

@section('title', 'Theme settings')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Theme settings</h1>
        <p class="mt-1 text-sm text-gray-500">Choose a theme and customise the look and feel of your public business page.</p>
    </div>

    @livewire(\App\Domains\Theme\Livewire\ThemeCustomizer::class, ['business' => $currentBusiness])
</div>
@endsection
