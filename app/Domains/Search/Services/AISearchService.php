<?php

namespace App\Domains\Search\Services;

use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;

class AISearchService
{
    protected SearchParser $parser;

    protected RankingService $ranking;

    public function __construct(SearchParser $parser, RankingService $ranking)
    {
        $this->parser = $parser;
        $this->ranking = $ranking;
    }

    public function search(string $query, ?array $userLocation = null): Collection
    {
        $parsed = $this->parser->parse($query);
        $searchTerm = $this->buildSearchTerm($parsed);

        $results = collect();

        $results = $results->merge(
            $this->searchBusinesses($searchTerm, $parsed)
        );

        if (in_array($parsed['query_type'], ['service', 'general', 'both'])) {
            $results = $results->merge(
                $this->searchServices($searchTerm, $parsed)
            );
        }

        if (in_array($parsed['query_type'], ['product', 'general', 'both'])) {
            $results = $results->merge(
                $this->searchProducts($searchTerm, $parsed)
            );
        }

        $results = $results->unique('id');

        $results = $this->applyModifierFilters($results, $parsed);

        $scoredResults = $results->map(function ($business) use ($userLocation) {
            return [
                'business' => $business,
                'score' => $this->ranking->calculateScore($business, $userLocation),
            ];
        });

        return $scoredResults->sortByDesc('score')->pluck('business')->values();
    }

    protected function buildSearchTerm(array $parsed): string
    {
        $terms = [$parsed['clean_query']];

        if (! empty($parsed['expanded_terms'])) {
            $terms = array_merge($terms, $parsed['expanded_terms']);
        }

        return implode(' ', array_unique($terms));
    }

    protected function searchBusinesses(string $searchTerm, array $parsed): Collection
    {
        $query = Business::search($searchTerm)
            ->where('is_active', true)
            ->where('is_discoverable', true);

        if ($parsed['location'] && $parsed['location']['type'] === 'city') {
            $query->where('city', 'like', "%{$parsed['location']['value']}%");
        }

        $results = $query->get();

        if ($parsed['service_category']) {
            $results = $results->filter(function ($business) use ($parsed) {
                return $business->category &&
                    (str_contains(strtolower($business->category->slug), strtolower($parsed['service_category'])) ||
                     str_contains(strtolower($business->category->name), strtolower($parsed['service_category'])));
            });
        }

        return $results;
    }

    protected function searchServices(string $searchTerm, array $parsed): Collection
    {
        $query = Service::search($searchTerm)
            ->where('is_active', true)
            ->where('is_public', true);

        $results = $query->get()
            ->filter(fn ($s) => $s->business && $s->business->is_active && $s->business->is_discoverable);

        if ($parsed['service_category']) {
            $results = $results->filter(function ($service) use ($parsed) {
                return $service->category &&
                    (str_contains(strtolower($service->category->slug), strtolower($parsed['service_category'])) ||
                     str_contains(strtolower($service->category->name), strtolower($parsed['service_category'])));
            });
        }

        if ($parsed['location'] && $parsed['location']['type'] === 'city') {
            $results = $results->filter(fn ($s) => $s->business &&
                str_contains(strtolower($s->business->city ?? ''), strtolower($parsed['location']['value'])));
        }

        return $results;
    }

    protected function searchProducts(string $searchTerm, array $parsed): Collection
    {
        $query = Product::search($searchTerm)
            ->where('is_active', true)
            ->where('is_public', true);

        $results = $query->get()
            ->filter(fn ($p) => $p->business && $p->business->is_active && $p->business->is_discoverable);

        if ($parsed['product_category']) {
            $results = $results->filter(function ($product) use ($parsed) {
                return $product->category &&
                    (str_contains(strtolower($product->category->slug), strtolower($parsed['product_category'])) ||
                     str_contains(strtolower($product->category->name), strtolower($parsed['product_category'])));
            });
        }

        if ($parsed['location'] && $parsed['location']['type'] === 'city') {
            $results = $results->filter(fn ($p) => $p->business &&
                str_contains(strtolower($p->business->city ?? ''), strtolower($parsed['location']['value'])));
        }

        return $results;
    }

    protected function applyModifierFilters(Collection $results, array $parsed): Collection
    {
        if (in_array('is_verified', $parsed['modifiers'])) {
            $results = $results->where('is_verified', true);
        }

        return $results->values();
    }
}
