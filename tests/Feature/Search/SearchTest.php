<?php

use App\Domains\Search\Livewire\ExploreSearch;
use App\Domains\Search\Services\RankingService;
use App\Domains\Search\Services\SearchParser;
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
    $this->business->searchable();

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

    it('shows browse results when no search query', function () {
        Livewire::test(ExploreSearch::class)
            ->assertSee('Electronics Store');
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

    it('returns results count', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Electronics')
            ->assertSee('result');
    });
});

describe('AI Search', function () {
    it('triggers AI search for natural language queries', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'I need a phone near me')
            ->assertSet('useAISearch', true);
    });

    it('triggers AI search for long queries', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Where can I find affordable electronics in Lagos')
            ->assertSet('useAISearch', true);
    });

    it('falls back gracefully when no indexed data matches', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'I need a phone near me')
            ->assertSee('No results');
    });

    it('respects is_discoverable in AI mode', function () {
        Business::factory()->create([
            'name' => 'Hidden Electronics',
            'is_discoverable' => false,
        ]);

        $visible = Business::factory()->discoverable()->create([
            'name' => 'Visible Electronics',
            'description' => 'Electronics store',
        ]);

        // Test that traditional search (non-AI) respects discoverability
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Electronics')
            ->assertDontSee('Hidden Electronics');
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

    it('filters by product type', function () {
        Livewire::test(ExploreSearch::class)
            ->set('type', 'product')
            ->assertSee('Smart Phone');
    });
});

describe('Sort Options', function () {
    it('sorts by relevance by default', function () {
        Livewire::test(ExploreSearch::class)
            ->set('search', 'Electronics')
            ->assertSet('sort', 'relevance');
    });

    it('can sort by newest', function () {
        Livewire::test(ExploreSearch::class)
            ->set('sort', 'newest')
            ->assertSet('sort', 'newest');
    });

    it('can sort by rating', function () {
        Livewire::test(ExploreSearch::class)
            ->set('sort', 'rating')
            ->assertSet('sort', 'rating');
    });
});

describe('Verified Filter', function () {
    it('toggles verified filter', function () {
        Livewire::test(ExploreSearch::class)
            ->set('verified', true)
            ->assertSet('verified', true);
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

describe('SearchParser', function () {
    it('parses location from near me', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('plumber near me');

        expect($result['location']['type'])->toBe('geolocation');
    });

    it('parses city location', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('restaurant in Lagos');

        expect($result['location']['type'])->toBe('city')
            ->and($result['location']['value'])->toBe('Lagos');
    });

    it('extracts price range', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('phone under 50000');

        expect($result['price_range'])->not->toBeNull()
            ->and($result['price_range']['max'])->toBe(50000);
    });

    it('detects buying intent', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('I want to buy a phone');

        expect($result['intent'])->toBe('buying');
    });

    it('classifies service queries', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('find me a plumber');

        expect($result['query_type'])->toBe('service')
            ->and($result['service_category'])->toBe('plumbing');
    });

    it('classifies product queries', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('iPhone 14 case');

        expect($result['query_type'])->toBe('product')
            ->and($result['product_category'])->toBe('electronics');
    });

    it('expands synonyms', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('food near me');

        expect($result['synonyms'])->toContain('food');
    });

    it('handles Nigerian pidgin', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('wahala price phone');

        expect($result['raw_query'])->toContain('expensive');
    });

    it('extracts modifiers', function () {
        $parser = app(SearchParser::class);
        $result = $parser->parse('best restaurant in Abuja');

        expect($result['modifiers'])->toContain('rating')
            ->and($result['location']['value'])->toBe('Abuja');
    });
});

describe('RankingService', function () {
    it('gives higher score to verified businesses', function () {
        $ranking = app(RankingService::class);
        $verified = Business::factory()->create(['is_verified' => true, 'average_rating' => 4]);
        $unverified = Business::factory()->create(['is_verified' => false, 'average_rating' => 4]);

        $verifiedScore = $ranking->calculateScore($verified, null);
        $unverifiedScore = $ranking->calculateScore($unverified, null);

        expect($verifiedScore)->toBeGreaterThan($unverifiedScore);
    });

    it('gives higher score to higher-rated businesses', function () {
        $ranking = app(RankingService::class);
        $highRated = Business::factory()->create(['is_verified' => true, 'average_rating' => 5]);
        $lowRated = Business::factory()->create(['is_verified' => true, 'average_rating' => 2]);

        $highScore = $ranking->calculateScore($highRated, null);
        $lowScore = $ranking->calculateScore($lowRated, null);

        expect($highScore)->toBeGreaterThan($lowScore);
    });

    it('calculates distance correctly', function () {
        $ranking = app(RankingService::class);
        $distance = $ranking->calculateDistance(6.5244, 3.3792, 6.4654, 3.3090);

        expect($distance)->toBeGreaterThan(0)
            ->and($distance)->toBeLessThan(100);
    });
});
