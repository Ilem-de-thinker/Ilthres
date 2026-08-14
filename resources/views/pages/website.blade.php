@extends('layouts.dashboard')

@section('title', 'Website Pages')

@section('content')
    @livewire(\App\Domains\Website\Livewire\PageManager::class)
@endsection
