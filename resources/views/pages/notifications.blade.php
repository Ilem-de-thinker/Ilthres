@extends('layouts.dashboard')

@section('title', 'Notifications')

@section('content')
    @livewire(\App\Domains\Notifications\Livewire\NotificationList::class)
@endsection
