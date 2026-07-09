<?php

use App\Domains\Marketing\Actions\CreateLink;
use App\Domains\Marketing\Actions\DeleteLink;
use App\Domains\Marketing\Actions\TrackClick;
use App\Domains\Marketing\DTOs\MarketingLinkData;
use App\Domains\Marketing\Livewire\CreateLink as CreateLinkComponent;
use App\Domains\Marketing\Livewire\LinkStats;
use App\Domains\Marketing\Livewire\MarketingLinks;
use App\Domains\Marketing\Policies\MarketingLinkPolicy;
use App\Models\Business;
use App\Models\LinkClick;
use App\Models\MarketingLink;
use App\Models\User;
use Livewire\Livewire;

uses()->group('marketing');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
});

describe('Marketing Links List', function () {
    it('renders marketing page', function () {
        $this->get('/dashboard/marketing')->assertSuccessful();
    });

    it('shows empty state', function () {
        Livewire::test(MarketingLinks::class)
            ->assertSuccessful();
    });

    it('lists marketing links', function () {
        MarketingLink::factory()->count(3)->create(['business_id' => $this->business->id]);

        Livewire::test(MarketingLinks::class)
            ->assertSuccessful();
    });
});

describe('Create Marketing Link', function () {
    it('renders create page', function () {
        $this->get('/dashboard/marketing/create')->assertSuccessful();
    });

    it('creates a link', function () {
        Livewire::test(CreateLinkComponent::class)
            ->set('name', 'Summer Sale')
            ->set('url', 'https://example.com/sale')
            ->call('save')
            ->assertRedirect('/dashboard/marketing');

        $this->assertDatabaseHas('marketing_links', [
            'business_id' => $this->business->id,
            'name' => 'Summer Sale',
            'is_active' => true,
        ]);
    });

    it('validates required fields', function () {
        Livewire::test(CreateLinkComponent::class)
            ->call('save')
            ->assertHasErrors(['name', 'url']);
    });

    it('validates URL format', function () {
        Livewire::test(CreateLinkComponent::class)
            ->set('name', 'Test')
            ->set('url', 'not-a-url')
            ->call('save')
            ->assertHasErrors(['url']);
    });

    it('auto-generates slug from name', function () {
        Livewire::test(CreateLinkComponent::class)
            ->set('name', 'My Special Link')
            ->assertSet('slug', Str::slug('My Special Link'));
    });
});

describe('Delete Marketing Link', function () {
    it('deletes a link', function () {
        $link = MarketingLink::factory()->create(['business_id' => $this->business->id]);

        app(DeleteLink::class)($link);

        expect(MarketingLink::find($link->id))->toBeNull();
    });
});

describe('Track Click', function () {
    it('records a click', function () {
        $link = MarketingLink::factory()->create(['business_id' => $this->business->id, 'is_active' => true]);

        app(TrackClick::class)($link);

        expect($link->clicks()->count())->toBe(1);
    });
});

describe('CreateLink Action', function () {
    it('creates a marketing link via action', function () {
        $data = new MarketingLinkData(
            businessId: $this->business->id,
            name: 'Promo Link',
            slug: 'promo-link',
            url: 'https://example.com/promo',
            description: 'A promo link',
            isActive: true,
        );

        $link = app(CreateLink::class)($data);

        expect($link)->toBeInstanceOf(MarketingLink::class)
            ->and($link->name)->toBe('Promo Link')
            ->and($link->url)->toBe('https://example.com/promo');
    });
});

describe('Redirect Route', function () {
    it('redirects to the link URL and records click', function () {
        $link = MarketingLink::factory()->create([
            'business_id' => $this->business->id,
            'url' => 'https://example.com/dest',
            'is_active' => true,
        ]);

        $this->get("/go/{$link->slug}")
            ->assertRedirect('https://example.com/dest');

        expect($link->clicks()->count())->toBe(1);
    });

    it('returns 404 for inactive link', function () {
        $link = MarketingLink::factory()->inactive()->create([
            'business_id' => $this->business->id,
        ]);

        $this->get("/go/{$link->slug}")->assertNotFound();
    });
});

describe('Link Stats', function () {
    it('renders link stats', function () {
        $link = MarketingLink::factory()->create(['business_id' => $this->business->id]);
        LinkClick::factory()->count(5)->create(['marketing_link_id' => $link->id]);

        Livewire::test(LinkStats::class)
            ->assertSuccessful();
    });
});

describe('Marketing Link Policy', function () {
    it('allows owner to view link', function () {
        $link = MarketingLink::factory()->create(['business_id' => $this->business->id]);

        expect((new MarketingLinkPolicy)->view($this->user, $link))->toBeTrue();
    });

    it('denies non-owner from viewing link', function () {
        $otherBusiness = Business::factory()->create();
        $link = MarketingLink::factory()->create(['business_id' => $otherBusiness->id]);

        expect((new MarketingLinkPolicy)->view($this->user, $link))->toBeFalse();
    });
});
