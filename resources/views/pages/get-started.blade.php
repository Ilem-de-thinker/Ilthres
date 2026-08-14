{{--
  Post-registration choice page — lets the user link an existing business or create a new one.
--}}
@extends('layouts.app')

@section('title', 'Get started')

@section('content')
    @livewire(\App\Domains\Authentication\Livewire\GetStarted::class)
@endsection
