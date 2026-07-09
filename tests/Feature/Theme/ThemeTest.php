<?php

use App\Domains\Theme\Actions\ActivateTheme;
use App\Domains\Theme\Actions\CustomizeTheme;
use App\Domains\Theme\Livewire\ThemeCustomizer;
use App\Domains\Theme\Livewire\ThemeList;
use App\Models\Business;
use App\Models\Theme;
use App\Models\User;
use Livewire\Livewire;

uses()->group('theme');

beforeEach(function () {
    $this->user = User::factory()->create(['onboarding_completed' => true]);
    $this->business = Business::factory()->productStore()->create(['user_id' => $this->user->id]);
    session(['current_business_id' => $this->business->id]);
    $this->actingAs($this->user);
    Theme::factory()->count(3)->create();
});

describe('Theme List', function () {
    it('renders themes page', function () {
        $this->get('/dashboard/theme')->assertSuccessful();
    });

    it('shows available themes', function () {
        Livewire::test(ThemeList::class)
            ->assertSuccessful();
    });
});

describe('Activate Theme', function () {
    it('activates a theme for a business', function () {
        $theme = Theme::first();

        app(ActivateTheme::class)($this->business, $theme);

        $activeTheme = $this->business->themes()
            ->wherePivot('is_active', true)
            ->first();

        expect($activeTheme->id)->toBe($theme->id);
    });

    it('deactivates previously active theme', function () {
        $theme1 = Theme::factory()->create();
        $theme2 = Theme::factory()->create();

        app(ActivateTheme::class)($this->business, $theme1);
        app(ActivateTheme::class)($this->business, $theme2);

        $activeCount = $this->business->themes()
            ->wherePivot('is_active', true)
            ->count();

        expect($activeCount)->toBe(1);
    });
});

describe('Customize Theme', function () {
    it('saves theme customization', function () {
        $theme = Theme::first();
        app(ActivateTheme::class)($this->business, $theme);

        $customization = [
            'colors' => [
                'primary' => '#FF0000',
                'secondary' => '#00FF00',
                'background' => '#FFFFFF',
                'text' => '#000000',
                'accent' => '#FF00FF',
            ],
        ];

        app(CustomizeTheme::class)($this->business, $customization);

        $pivot = $this->business->themes()->where('theme_id', $theme->id)->first()->pivot;
        expect(json_decode($pivot->customization, true))->toBe($customization);
    });
});

describe('Theme Customizer Livewire', function () {
    it('renders theme customizer', function () {
        $theme = Theme::first();
        app(ActivateTheme::class)($this->business, $theme);

        Livewire::test(ThemeCustomizer::class, ['business' => $this->business])
            ->assertSuccessful();
    });

    it('saves customization', function () {
        $theme = Theme::first();
        app(ActivateTheme::class)($this->business, $theme);

        Livewire::test(ThemeCustomizer::class, ['business' => $this->business])
            ->set('primary', '#FF0000')
            ->call('saveCustomization')
            ->assertSuccessful();
    });
});

describe('Theme Scoping', function () {
    it('shows only themes matching business type', function () {
        $storeTheme = Theme::factory()->forBusinessType('product_store')->create();
        $globalTheme = Theme::factory()->create(['business_type' => null]);
        $serviceTheme = Theme::factory()->forBusinessType('service_business')->create();

        $themeIds = Theme::forBusinessType('product_store')->pluck('id')->toArray();

        expect(in_array($storeTheme->id, $themeIds))->toBeTrue()
            ->and(in_array($globalTheme->id, $themeIds))->toBeTrue()
            ->and(in_array($serviceTheme->id, $themeIds))->toBeFalse();
    });
});
