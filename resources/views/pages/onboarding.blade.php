{{--
  Onboarding wizard page — wraps the Business\OnboardingWizard Livewire component.
  Steps: WhatsApp → Business Type → Pricing → Setup → Dashboard.
--}}
@extends('layouts.app')

@section('title', 'Set up your business')

@section('content')
    @livewire(App\Domains\Business\Livewire\OnboardingWizard::class)
@endsection
