<?php

use App\Domains\Administration\Livewire\AdminDashboard;
use App\Domains\Administration\Livewire\BusinessManager;
use App\Domains\Administration\Livewire\ThemeManager;
use App\Domains\Administration\Livewire\UserManager;
use App\Models\Business;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

uses()->group('admin');

beforeEach(function () {
    $this->admin = User::factory()->create(['is_admin' => true, 'onboarding_completed' => true]);
    $this->user = User::factory()->create(['is_admin' => false, 'onboarding_completed' => true]);
});

describe('Admin Middleware', function () {
    it('allows admin users to access admin routes', function () {
        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertSuccessful();
    });

    it('redirects non-admin users from admin routes', function () {
        $this->actingAs($this->user)
            ->get('/admin')
            ->assertRedirect('/dashboard');
    });

    it('redirects guests from admin routes', function () {
        $this->get('/admin')
            ->assertRedirect('/login');
    });
});

describe('Admin Dashboard', function () {
    it('renders admin dashboard', function () {
        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertSuccessful();
    });

    it('shows platform statistics', function () {
        User::factory()->count(3)->create();

        $this->actingAs($this->admin);

        Livewire::test(AdminDashboard::class)
            ->assertSuccessful();
    });
});

describe('User Manager', function () {
    it('renders user list', function () {
        $this->actingAs($this->admin)
            ->get('/admin/users')
            ->assertSuccessful();
    });

    it('allows admin to toggle admin status', function () {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('toggleAdmin', $this->user->id)
            ->assertSuccessful();

        expect(User::find($this->user->id)->is_admin)->toBeTrue();
    });

    it('prevents self-demotion', function () {
        Livewire::actingAs($this->admin)
            ->test(UserManager::class)
            ->call('toggleAdmin', $this->admin->id)
            ->assertSuccessful();

        expect(User::find($this->admin->id)->is_admin)->toBeTrue();
    });

    it('searches users by name', function () {
        $this->actingAs($this->admin);

        Livewire::test(UserManager::class)
            ->set('search', $this->user->name)
            ->assertSee($this->user->name);
    });
});

describe('Business Manager', function () {
    it('renders business list', function () {
        Business::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->admin)
            ->get('/admin/businesses')
            ->assertSuccessful();
    });

    it('toggles business active status', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(BusinessManager::class)
            ->call('toggleActive', $business->id)
            ->assertSuccessful();

        expect($business->fresh()->is_active)->toBeFalse();
    });

    it('filters businesses by status', function () {
        Business::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        $this->actingAs($this->admin);

        Livewire::test(BusinessManager::class)
            ->set('filterStatus', 'active')
            ->assertSuccessful();
    });

    it('changes subscription plan for a business', function () {
        $oldPlan = Plan::factory()->create(['name' => 'Free', 'price' => 0]);
        $newPlan = Plan::factory()->create(['name' => 'Pro', 'price' => 5000]);
        $business = Business::factory()->create(['user_id' => $this->admin->id]);
        Subscription::factory()->create([
            'business_id' => $business->id,
            'plan_id' => $oldPlan->id,
        ]);

        Livewire::actingAs($this->admin)
            ->test(BusinessManager::class)
            ->call('startPlanChange', $business->id)
            ->set('selectedPlanId', $newPlan->id)
            ->call('changePlan', $business->id)
            ->assertSuccessful();

        expect($business->fresh()->subscription->plan_id)->toBe($newPlan->id);
    });
});

describe('Theme Manager', function () {
    it('renders theme list', function () {
        Theme::factory()->create();

        $this->actingAs($this->admin)
            ->get('/admin/themes')
            ->assertSuccessful();
    });

    it('toggles theme active status', function () {
        $theme = Theme::factory()->create(['is_active' => true]);

        Livewire::actingAs($this->admin)
            ->test(ThemeManager::class)
            ->call('toggleActive', $theme->id)
            ->assertSuccessful();

        expect($theme->fresh()->is_active)->toBeFalse();
    });
});
