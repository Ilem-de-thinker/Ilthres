{{--
  Media library page — wraps the Media\MediaLibrary Livewire component.
  Shows upload form, collection filters, and file grid.
--}}
@extends('layouts.dashboard')

@section('title', 'Media library')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Media library</h1>
        <p class="mt-1 text-sm text-gray-500">Upload, manage, and organise your business images and files.</p>
    </div>

    @livewire(\App\Domains\Media\Livewire\MediaLibrary::class)
</div>
@endsection
