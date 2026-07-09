@extends('layouts.auth')

@section('title', 'Sign in')

@section('auth-content')
    <a href="/" class="hidden lg:inline-flex items-center gap-1 text-sm text-gray-500 hover:text-dark-gold transition mb-8">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to home
    </a>

    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h1>
    <p class="mt-2 text-sm text-gray-600">
        New here?
        <a href="{{ route('register') }}" class="font-medium text-dark-gold hover:text-dark-gold-500">Create an account</a>
    </p>

    <div class="mt-8">
        @livewire(App\Domains\Authentication\Livewire\Login::class)
    </div>
@endsection
