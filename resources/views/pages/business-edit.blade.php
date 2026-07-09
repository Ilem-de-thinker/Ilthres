{{--
  Edit business page — wraps the Business\EditBusiness Livewire component.
  Comprehensive form with sections: details, contact, logo, theme.
--}}
@extends('layouts.dashboard')

@section('title', 'Edit business')

@section('content')
<div class="mx-auto max-w-2xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit business</h1>
        <p class="mt-1 text-sm text-gray-500">Manage your business details, branding, and theme.</p>
    </div>

    @livewire(\App\Domains\Business\Livewire\EditBusiness::class)
</div>
@endsection
