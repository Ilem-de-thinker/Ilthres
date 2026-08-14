<?php

use App\Models\Plan;
use App\Models\PlatformSetting;
use App\Models\User;
use Database\Seeders\PlanSeeder;

use function Pest\Laravel\seed;

beforeEach(function () {
    seed(PlanSeeder::class);
});

it('grants professional plan when pricing is disabled and subscription is expired', function () {
    PlatformSetting::set('pricing_enabled', false);

    $user = User::factory()->create(['onboarding_completed' => true]);
    $business = $user->businesses()->create([
        'name' => 'Test Business',
        'slug' => 'test-business-'.uniqid(),
        'type' => 'product_store',
        'is_active' => true,
    ]);

    // Create an expired subscription — normally this would block access.
    $plan = Plan::where('slug', 'professional')->first();
    $business->subscription()->create([
        'plan_id' => $plan->id,
        'status' => 'expired',
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonth(),
    ]);

    session(['current_business_id' => $business->id]);

    $response = $this->actingAs($user)->get('/dashboard/media');

    $response->assertSuccessful();
});

it('grants professional plan when pricing is disabled and no subscription exists', function () {
    PlatformSetting::set('pricing_enabled', false);

    $user = User::factory()->create(['onboarding_completed' => true]);
    $business = $user->businesses()->create([
        'name' => 'Test Business',
        'slug' => 'test-business-'.uniqid(),
        'type' => 'product_store',
        'is_active' => true,
    ]);

    // No subscription at all.
    session(['current_business_id' => $business->id]);

    $response = $this->actingAs($user)->get('/dashboard/media');

    $response->assertSuccessful();
});

it('blocks expired subscription when pricing is enabled', function () {
    PlatformSetting::set('pricing_enabled', true);

    $user = User::factory()->create(['onboarding_completed' => true]);
    $business = $user->businesses()->create([
        'name' => 'Test Business',
        'slug' => 'test-business-'.uniqid(),
        'type' => 'product_store',
        'is_active' => true,
    ]);

    $plan = Plan::where('slug', 'professional')->first();
    $business->subscription()->create([
        'plan_id' => $plan->id,
        'status' => 'expired',
        'starts_at' => now()->subMonths(2),
        'ends_at' => now()->subMonth(),
    ]);

    session(['current_business_id' => $business->id]);

    $response = $this->actingAs($user)->get('/dashboard/media');

    $response->assertStatus(403);
});

it('allows access when pricing is enabled and business has active subscription', function () {
    PlatformSetting::set('pricing_enabled', true);

    $user = User::factory()->create(['onboarding_completed' => true]);
    $business = $user->businesses()->create([
        'name' => 'Test Business',
        'slug' => 'test-business-'.uniqid(),
        'type' => 'product_store',
        'is_active' => true,
    ]);

    $plan = Plan::where('slug', 'professional')->first();
    $business->subscription()->create([
        'plan_id' => $plan->id,
        'status' => 'active',
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
    ]);

    session(['current_business_id' => $business->id]);

    $response = $this->actingAs($user)->get('/dashboard/media');

    $response->assertSuccessful();
});
