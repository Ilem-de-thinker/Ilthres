@extends('layouts.auth')

@section('title', 'Reset password')

@section('auth-content')
    <a href="/" class="hidden lg:inline-flex items-center gap-1 text-sm text-gray-500 hover:text-dark-gold transition mb-8">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to home
    </a>

    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Set new password</h1>

    <div class="mt-8">
        @livewire(App\Domains\Authentication\Livewire\ResetPassword::class, ['token' => $token])
    </div>
@endsection
