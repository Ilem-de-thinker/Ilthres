{{--
  Link business page — lets the user search for and claim an existing business.
--}}
@extends('layouts.app')

@section('title', 'Link your business')

@section('content')
    @livewire(\App\Domains\Authentication\Livewire\LinkBusiness::class)
@endsection
