<?php

use App\Domains\Business\Actions\CreateBusiness;
use App\Domains\Business\DTOs\BusinessData;
use App\Domains\Business\Livewire\OnboardingWizard;
use App\Models\Business;
use App\Models\Category;
use App\Models\Plan;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

uses()->group('onboarding');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_step' => 0, 'onboarding_completed' => false]);
    $this->actingAs($this->user);
    Category::factory()->create(['name' => 'Fashion']);
    Plan::factory()->free()->create(['name' => 'Free', 'slug' => 'free']);
    Theme::factory()->create(['name' => 'Default', 'slug' => 'default']);
});

describe('Onboarding Wizard', function () {
    it('redirects authenticated users at step 0 to get-started page', function () {
        $this->get('/dashboard')->assertRedirect('/get-started');
    });

    it('renders onboarding page', function () {
        $this->get('/onboarding')->assertSuccessful();
    });

    it('shows step 1 initially', function () {
        Livewire::test(OnboardingWizard::class)
            ->assertSee('Link your WhatsApp');
    });

    it('progresses through all steps', function () {
        $component = Livewire::test(OnboardingWizard::class);

        $component->set('whatsapp', '8012345678')
            ->call('nextStep')
            ->assertSet('step', 2)
            ->assertSee('What type of business');

        $component->set('businessType', 'product_store')
            ->call('nextStep')
            ->assertSet('step', 3)
            ->assertSee('Choose your plan');

        $component->set('selectedPlanId', Plan::first()->id)
            ->call('nextStep')
            ->assertSet('step', 4)
            ->assertSee('Set up your business');
    });

    it('validates whatsapp number in step 1', function () {
        Livewire::test(OnboardingWizard::class)
            ->call('nextStep')
            ->assertHasErrors('whatsapp');
    });

    it('validates plan selection in step 3', function () {
        Livewire::test(OnboardingWizard::class)
            ->set('whatsapp', '8012345678')
            ->call('nextStep')
            ->set('businessType', 'product_store')
            ->call('nextStep')
            ->call('nextStep')
            ->assertHasErrors('selectedPlanId');
    });

    it('validates business name in step 4', function () {
        Livewire::test(OnboardingWizard::class)
            ->set('whatsapp', '8012345678')
            ->call('nextStep')
            ->set('businessType', 'product_store')
            ->call('nextStep')
            ->set('selectedPlanId', Plan::first()->id)
            ->call('nextStep')
            ->call('nextStep')
            ->assertHasErrors('businessName');
    });

    it('can go back to previous step', function () {
        Livewire::test(OnboardingWizard::class)
            ->set('whatsapp', '8012345678')
            ->call('nextStep')
            ->call('prevStep')
            ->assertSet('step', 1);
    });
});

describe('CreateBusiness Action', function () {
    it('creates a business from valid data', function () {
        $data = new BusinessData(
            userId: $this->user->id,
            type: Business::TYPE_PRODUCT_STORE,
            name: 'Test Store',
            slug: 'test-store',
            description: 'A test store',
            email: 'store@example.com',
            phone: null,
            whatsapp: null,
            address: null,
            city: null,
            state: null,
            country: 'Nigeria',
            categoryId: Category::factory()->create()->id,
            logo: null,
            cover: null,
        );

        $business = app(CreateBusiness::class)($data);

        expect($business)->toBeInstanceOf(Business::class)
            ->and($business->name)->toBe('Test Store')
            ->and($business->user_id)->toBe($this->user->id);
    });
});
