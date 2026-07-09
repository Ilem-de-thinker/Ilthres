<?php

use App\Domains\Service\Actions\CreateService;
use App\Domains\Service\Actions\DeleteService;
use App\Domains\Service\Actions\ToggleServiceStatus;
use App\Domains\Service\Actions\UpdateService;
use App\Domains\Service\DTOs\ServiceData;
use App\Domains\Service\Livewire\ServiceEdit;
use App\Domains\Service\Livewire\ServiceForm;
use App\Domains\Service\Livewire\ServiceList;
use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Livewire\Livewire;

uses()->group('service');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->serviceBusiness()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Service List', function () {
    it('renders service list page', function () {
        $this->get('/dashboard/services')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(ServiceList::class)
            ->assertSee('No services yet');
    });

    it('lists services', function () {
        Service::factory()->count(3)->create(['business_id' => $this->business->id]);

        Livewire::test(ServiceList::class)
            ->assertSuccessful();
    });
});

describe('Service Create', function () {
    it('renders create service page', function () {
        $this->get('/dashboard/services/create')->assertSuccessful();
    });

    it('creates a service via form', function () {
        Livewire::test(ServiceForm::class)
            ->set('name', 'New Service')
            ->set('price', 5000)
            ->set('durationMinutes', 60)
            ->set('description', 'A great service')
            ->call('saveCreate')
            ->assertDispatched('service-saved');

        $this->assertDatabaseHas('services', [
            'business_id' => $this->business->id,
            'name' => 'New Service',
            'price' => 5000.00,
        ]);
    });

    it('validates required fields', function () {
        Livewire::test(ServiceForm::class)
            ->call('saveCreate')
            ->assertHasErrors(['name', 'price']);
    });
});

describe('Service Edit', function () {
    it('renders edit service page', function () {
        $service = Service::factory()->create(['business_id' => $this->business->id]);

        $this->get("/dashboard/services/{$service->id}/edit")->assertSuccessful();
    });

    it('updates service details', function () {
        $service = Service::factory()->create(['business_id' => $this->business->id, 'name' => 'Old Name']);

        Livewire::test(ServiceEdit::class, ['serviceId' => $service->id])
            ->set('name', 'Updated Name')
            ->set('price', 8000)
            ->call('save')
            ->assertRedirect();

        expect($service->fresh()->name)->toBe('Updated Name');
    });
});

describe('Service Actions', function () {
    it('toggles service status', function () {
        $service = Service::factory()->create([
            'business_id' => $this->business->id,
            'is_active' => true,
        ]);

        app(ToggleServiceStatus::class)($service);

        expect($service->fresh()->is_active)->toBeFalse();
    });

    it('soft deletes a service', function () {
        $service = Service::factory()->create(['business_id' => $this->business->id]);

        app(DeleteService::class)($service);

        expect(Service::find($service->id))->toBeNull()
            ->and(Service::withTrashed()->find($service->id))->not->toBeNull();
    });

    it('creates service via action', function () {
        $data = new ServiceData(
            businessId: $this->business->id,
            name: 'Action Service',
            slug: 'action-service',
            description: 'Created via action',
            price: 3000.00,
            currency: 'NGN',
            durationMinutes: 45,
            images: null,
            isActive: true,
            isPublic: true,
            sortOrder: 0,
            categoryId: null,
        );

        $service = app(CreateService::class)($data);

        expect($service)->toBeInstanceOf(Service::class)
            ->and($service->name)->toBe('Action Service');
    });

    it('updates service via action', function () {
        $service = Service::factory()->create(['business_id' => $this->business->id]);

        $data = new ServiceData(
            businessId: $this->business->id,
            name: 'Updated Action',
            slug: 'updated-action',
            description: 'Updated',
            price: 6000.00,
            currency: 'NGN',
            durationMinutes: 30,
            images: null,
            isActive: true,
            isPublic: true,
            sortOrder: 0,
            categoryId: null,
        );

        $updated = app(UpdateService::class)($service, $data);

        expect($updated->name)->toBe('Updated Action');
    });
});

describe('Service Authorization', function () {
    it('denies access to another business service', function () {
        $otherBusiness = Business::factory()->serviceBusiness()->create();
        $service = Service::factory()->create(['business_id' => $otherBusiness->id]);

        $this->get("/dashboard/services/{$service->id}/edit")->assertForbidden();
    });
});
