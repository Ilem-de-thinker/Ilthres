<?php

use App\Domains\Subscription\Actions\CancelSubscription;
use App\Domains\Subscription\Actions\ChangePlan;
use App\Domains\Subscription\Actions\CreateSubscription;
use App\Domains\Subscription\DTOs\SubscriptionData;
use App\Domains\Subscription\Livewire\PricingPage;
use App\Domains\Subscription\Livewire\SubscriptionStatus;
use App\Domains\Subscription\Policies\SubscriptionPolicy;
use App\Models\Business;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;

uses()->group('subscription');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
    $this->plan = Plan::factory()->create(['name' => 'Pro', 'price' => 5000, 'is_active' => true, 'sort_order' => 2]);
    $this->freePlan = Plan::factory()->free()->create(['name' => 'Free', 'is_active' => true, 'sort_order' => 1]);
});

describe('Pricing Page', function () {
    it('renders pricing page', function () {
        $this->get('/pricing')->assertSuccessful();
    });

    it('shows active plans', function () {
        Livewire::test(PricingPage::class)
            ->assertSee('Free')
            ->assertSee('Pro');
    });

    it('hides inactive plans', function () {
        Plan::factory()->inactive()->create(['name' => 'Hidden']);

        Livewire::test(PricingPage::class)
            ->assertDontSee('Hidden');
    });
});

describe('Create Subscription', function () {
    it('creates an active subscription', function () {
        $data = new SubscriptionData(
            businessId: $this->business->id,
            planId: $this->plan->id,
            status: 'active',
            startsAt: now(),
            endsAt: now()->addMonth(),
            gateway: 'paystack',
            gatewayReference: 'ref-123',
        );

        $subscription = app(CreateSubscription::class)($data);

        expect($subscription)->toBeInstanceOf(Subscription::class)
            ->and($subscription->status)->toBe('active')
            ->and($subscription->plan_id)->toBe($this->plan->id);
    });
});

describe('Cancel Subscription', function () {
    it('cancels an active subscription', function () {
        $subscription = Subscription::factory()->create([
            'business_id' => $this->business->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
        ]);

        app(CancelSubscription::class)($subscription);

        expect($subscription->fresh()->status)->toBe('cancelled')
            ->and($subscription->fresh()->cancelled_at)->not->toBeNull();
    });
});

describe('Change Plan', function () {
    it('changes subscription plan', function () {
        $subscription = Subscription::factory()->create([
            'business_id' => $this->business->id,
            'plan_id' => $this->freePlan->id,
            'status' => 'active',
        ]);

        app(ChangePlan::class)($subscription, $this->plan);

        expect($subscription->fresh()->plan_id)->toBe($this->plan->id);
    });
});

describe('Subscription Status Livewire', function () {
    it('renders subscription status', function () {
        Subscription::factory()->create([
            'business_id' => $this->business->id,
            'plan_id' => $this->plan->id,
            'status' => 'active',
        ]);

        Livewire::test(SubscriptionStatus::class)
            ->assertSuccessful()
            ->assertSee('Pro');
    });

    it('shows current plan name when subscription exists', function () {
        Livewire::test(SubscriptionStatus::class)
            ->assertSee('Free');
    });
});

describe('Subscription Policy', function () {
    it('allows owner to view subscription', function () {
        $subscription = Subscription::factory()->create(['business_id' => $this->business->id]);

        expect((new SubscriptionPolicy)->view($this->user, $subscription))->toBeTrue();
    });

    it('denies non-owner from viewing subscription', function () {
        $otherBusiness = Business::factory()->create();
        $subscription = Subscription::factory()->create(['business_id' => $otherBusiness->id]);

        expect((new SubscriptionPolicy)->view($this->user, $subscription))->toBeFalse();
    });
});

describe('Subscription Routes', function () {
    it('renders subscription page', function () {
        $this->get('/dashboard/subscription')->assertSuccessful();
    });
});
