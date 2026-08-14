@extends('layouts.dashboard')

@section('title', 'Orders')

@section('content')
<div class="max-w-5xl">
    @livewire(\App\Domains\Order\Livewire\SellerOrders::class)
</div>
@endsection
