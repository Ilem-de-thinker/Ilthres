<?php

use App\Models\Plan;
use Database\Seeders\PlanSeeder;

use function Pest\Laravel\seed;

it('has a linked_business plan in the database', function () {
    seed(PlanSeeder::class);

    $plan = Plan::where('slug', 'linked_business')->first();

    expect($plan)->not->toBeNull();
    expect($plan->name)->toBe('Linked Business');
    expect((float) $plan->price)->toBe(7500.00);
    expect($plan->currency)->toBe('NGN');
    expect($plan->interval)->toBe('monthly');
    expect($plan->is_active)->toBeTrue();
});

it('linked_business plan has correct features', function () {
    seed(PlanSeeder::class);

    $plan = Plan::where('slug', 'linked_business')->first();

    // features is cast to array via model
    expect($plan->features)->toContain('Business profile on Ilthres');
    expect($plan->features)->toContain('Link your external website');
});

it('linked_business plan config exists', function () {
    $limits = config('plans.linked_business');

    expect($limits)->not->toBeNull();
    expect($limits['max_media'])->toBe(5);
    expect($limits['max_products'])->toBe(0);
    expect($limits['max_services'])->toBe(0);
    expect($limits['premium_themes'])->toBeFalse();
    expect($limits['custom_domain'])->toBeFalse();
});

it('has all four plans active', function () {
    seed(PlanSeeder::class);

    $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();

    expect($plans->count())->toBe(4);
    expect($plans->pluck('slug')->toArray())->toBe(['free', 'professional', 'business', 'linked_business']);
});
