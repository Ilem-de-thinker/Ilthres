<?php

use App\Domains\Search\Livewire\ExploreSearch;
use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Livewire\Livewire;

uses()->group('search');

beforeEach(function () {
    $this->category = Category::factory()->create(['name' => 'Electronics']);
    $this->business = Business::factory()->productStore()
        ->discoverable()
        ->create(['name' => 'Electronics Store', 'category_id' => $this->category->id, 'description' => 'Smart Phone and accessories']);
    Product::factory()
        ->public()
        ->create(['business_id' => $this->business->id, 'name' => 'Smart Phone']);
    Product::factory()
        ->create(['business_id' => $this->business->id, 'name' => 'Hidden Item']);
});

describe('Explore Page', function () {
    it('renders explore page', function () {
        $this->get('/explore')->assertSuccessful();
    });

    it('shows discoverable businesses', function () {
        Livewire::test(ExploreSearch::class)
            ->assertSee('Electronics Store');
    });

    it('hides non-discoverable businesses', function () {
        $hidden = Business::factory()->create(['name' => 'Hidden Business', 'is_discoverable' => false]);
        Product::factory()->public()->create(['business_id' => $hidden->id, 'name' => 'Hidden Product']);

        Livewire::test(ExploreSearch::class)
            ->assertDontSee('Hidden Business');
    });
});

describe('Search', function () {
    it('searches by keyword in business name', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Electronics')
            ->assertSee('Electronics Store');
    });

    it('searches by keyword in product name', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Smart Phone')
            ->assertSee('Smart Phone');
    });

    it('does not return non-public products', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Hidden')
            ->assertDontSee('Hidden Item');
    });
});

describe('Category Filter', function () {
    it('filters by category', function () {
        Livewire::test(ExploreSearch::class)
            ->set('categoryId', $this->category->id)
            ->assertSee('Electronics Store');
    });

    it('shows no results for unmatched category', function () {
        $otherCategory = Category::factory()->create(['name' => 'Fashion']);

        Livewire::test(ExploreSearch::class)
            ->set('categoryId', $otherCategory->id)
            ->assertDontSee('Electronics Store');
    });
});

describe('Type Filter', function () {
    it('filters by business type', function () {
        Livewire::test(ExploreSearch::class)
            ->set('type', 'business')
            ->assertSee('Electronics Store');
    });
});

describe('Discoverability Toggles', function () {
    it('defaults business to non-discoverable', function () {
        expect(Business::factory()->create()->is_discoverable)->toBeFalse();
    });

    it('defaults product to non-public', function () {
        expect(Product::factory()->create()->is_public)->toBeFalse();
    });

    it('defaults service to non-public', function () {
        expect(Service::factory()->create()->is_public)->toBeFalse();
    });
});
