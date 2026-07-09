@extends('layouts.dashboard')

@section('title', 'Billing')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Billing</h1>
        <p class="mt-1 text-sm text-gray-500">View your payment history and invoices.</p>
    </div>

    @livewire(\App\Domains\Payment\Livewire\BillingHistory::class)
</div>
@endsection
