<?php

use App\Domains\Business\Actions\CreateBusiness;
use App\Domains\Business\Actions\DeleteBusiness;
use App\Domains\Business\Actions\UpdateBusiness;
use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Livewire\CreateBusiness as CreateBusinessComponent;
use App\Domains\Business\Livewire\EditBusiness;
use App\Domains\Business\Policies\BusinessPolicy;
use App\Models\Business;
use App\Models\Category;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

uses()->group('business');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->actingAs($this->user);
    Category::factory()->create(['name' => 'Fashion']);
});

describe('Create Business', function () {
    it('renders create business page', function () {
        $this->get('/business/create')->assertSuccessful();
    });

    it('creates a product store business', function () {
        $theme = Theme::factory()->create();

        Livewire::test(CreateBusinessComponent::class)
            ->set('name', 'My Store')
            ->set('type', Business::TYPE_PRODUCT_STORE)
            ->set('description', 'A great store')
            ->set('categoryId', Category::first()->id)
            ->set('themeId', $theme->id)
            ->call('save')
            ->assertRedirect();

        $this->assertDatabaseHas('businesses', [
            'name' => 'My Store',
            'user_id' => $this->user->id,
            'type' => Business::TYPE_PRODUCT_STORE,
        ]);
    });

    it('creates a service business', function () {
        $theme = Theme::factory()->create();

        Livewire::test(CreateBusinessComponent::class)
            ->set('name', 'My Service')
            ->set('type', Business::TYPE_SERVICE_BUSINESS)
            ->set('description', 'A great service')
            ->set('categoryId', Category::first()->id)
            ->set('themeId', $theme->id)
            ->call('save')
            ->assertRedirect();

        $this->assertDatabaseHas('businesses', [
            'name' => 'My Service',
            'user_id' => $this->user->id,
            'type' => Business::TYPE_SERVICE_BUSINESS,
        ]);
    });

    it('validates required fields', function () {
        Livewire::test(CreateBusinessComponent::class)
            ->call('save')
            ->assertHasErrors(['name', 'themeId']);
    });
});

describe('Edit Business', function () {
    it('renders edit business page', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);
        session(['current_business_id' => $business->id]);

        $this->get('/dashboard/business/edit')->assertSuccessful();
    });

    it('updates business details', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);
        session(['current_business_id' => $business->id]);

        Livewire::test(EditBusiness::class)
            ->set('name', 'Updated Name')
            ->set('description', 'Updated description')
            ->set('email', 'updated@example.com')
            ->set('city', 'Lagos')
            ->call('save')
            ->assertSuccessful();

        expect($business->fresh()->name)->toBe('Updated Name')
            ->and($business->fresh()->email)->toBe('updated@example.com');
    });

    it('validates required fields on edit', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);
        session(['current_business_id' => $business->id]);

        Livewire::test(EditBusiness::class)
            ->set('name', '')
            ->call('save')
            ->assertHasErrors('name');
    });
});

describe('Business Policy', function () {
    it('allows owner to view their business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        expect((new BusinessPolicy)->view($this->user, $business))->toBeTrue();
    });

    it('allows anyone to view public business profile', function () {
        $otherUser = User::factory()->create();
        $business = Business::factory()->create(['user_id' => $otherUser->id]);

        expect((new BusinessPolicy)->view($this->user, $business))->toBeTrue();
    });

    it('allows owner to update their business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        expect((new BusinessPolicy)->update($this->user, $business))->toBeTrue();
    });

    it('denies non-owner from updating business', function () {
        $otherUser = User::factory()->create();
        $business = Business::factory()->create(['user_id' => $otherUser->id]);

        expect((new BusinessPolicy)->update($this->user, $business))->toBeFalse();
    });

    it('allows owner to delete their business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        expect((new BusinessPolicy)->delete($this->user, $business))->toBeTrue();
    });
});

describe('Switching Businesses', function () {
    it('switches current business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        $this->get("/dashboard/switch/{$business->id}")
            ->assertRedirect('/dashboard');

        expect(session('current_business_id'))->toBe($business->id);
    });

    it('denies switching to business owned by another user', function () {
        $otherUser = User::factory()->create();
        $business = Business::factory()->create(['user_id' => $otherUser->id]);

        $this->get("/dashboard/switch/{$business->id}")->assertForbidden();
    });
});

describe('Delete Business Action', function () {
    it('soft deletes a business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        app(DeleteBusiness::class)($business);

        expect(Business::find($business->id))->toBeNull()
            ->and(Business::withTrashed()->find($business->id))->not->toBeNull();
    });
});

describe('UpdateBusiness Action', function () {
    it('updates business fields', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id]);

        $data = new BusinessData(
            userId: $this->user->id,
            type: $business->type,
            name: 'New Name',
            slug: 'new-name',
            description: 'New description',
            email: 'new@example.com',
            phone: '1234567890',
            whatsapp: null,
            address: '123 Main St',
            city: 'Lagos',
            state: 'Lagos',
            country: 'Nigeria',
            categoryId: null,
            logo: null,
            cover: null,
        );

        $result = app(UpdateBusiness::class)($business, $data);

        expect($result->name)->toBe('New Name')
            ->and($result->email)->toBe('new@example.com')
            ->and($result->city)->toBe('Lagos');
    });
});

describe('Public Profile', function () {
    it('renders public business profile', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id, 'is_active' => true]);

        $this->get("/{$business->slug}")->assertSuccessful();
    });

    it('shows 404 for inactive business', function () {
        $business = Business::factory()->create(['user_id' => $this->user->id, 'is_active' => false]);

        $this->get("/{$business->slug}")->assertNotFound();
    });

    it('renders product detail page', function () {
        $business = Business::factory()->productStore()->create(['user_id' => $this->user->id, 'is_active' => true]);
        $product = \App\Models\Product::factory()->create(['business_id' => $business->id, 'is_active' => true]);

        $this->get("/{$business->slug}/product/{$product->slug}")->assertSuccessful();
    });

    it('renders service detail page', function () {
        $business = Business::factory()->serviceBusiness()->create(['user_id' => $this->user->id, 'is_active' => true]);
        $service = \App\Models\Service::factory()->create(['business_id' => $business->id, 'is_active' => true]);

        $this->get("/{$business->slug}/service/{$service->slug}")->assertSuccessful();
    });
});
