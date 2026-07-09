@extends('layouts.dashboard')

@section('title', 'Edit Promotion')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Promotion</h1>
        <p class="mt-1 text-sm text-gray-500">Update your promotional campaign.</p>
    </div>

    @livewire(\App\Domains\Promotion\Livewire\PromotionForm::class, ['promotionId' => $promotion->id], key($promotion->id))
</div>
@endsection
