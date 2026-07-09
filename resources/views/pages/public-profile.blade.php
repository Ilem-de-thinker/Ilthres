{{--
  Public-facing business profile page.
  Wraps the Business\PublicProfile Livewire component which handles
  theme CSS variable injection and business-type-adapted content.
--}}
@extends('layouts.app')

@section('title', $business->name)

@section('content')
    @livewire(\App\Domains\Business\Livewire\PublicProfile::class, ['business' => $business], key($business->id))
@endsection
