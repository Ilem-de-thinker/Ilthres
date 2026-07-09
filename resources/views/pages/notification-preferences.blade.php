@extends('layouts.dashboard')

@section('title', 'Notification Preferences')

@section('content')
    @livewire(\App\Domains\Notifications\Livewire\NotificationPreferences::class)
@endsection
