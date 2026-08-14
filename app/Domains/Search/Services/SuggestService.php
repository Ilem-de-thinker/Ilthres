<?php

namespace App\Domains\Search\Services;

use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;

class SuggestService
{
    protected int $maxSuggestions;

    protected int $minChars;

    public function __construct()
    {
        $this->maxSuggestions = config('search.max_suggestions', 8);
        $this->minChars = config('search.suggestion_min_chars', 2);
    }

    /**
     * Get autocomplete suggestions for a partial query.
     */
    public function suggest(string $query, ?array $userLocation = null): array
    {
        $query = trim($query);

        if (strlen($query) < $this->minChars) {
            return $this->getPopularSuggestions();
        }

        $suggestions = collect();

        $suggestions = $suggestions->merge(
            $this->suggestBusinesses($query)
        );

        $suggestions = $suggestions->merge(
            $this->suggestProducts($query)
        );

        $suggestions = $suggestions->merge(
            $this->suggestServices($query)
        );

        $suggestions = $suggestions->unique('text')
            ->take($this->maxSuggestions)
            ->values();

        return $suggestions->toArray();
    }

    /**
     * Get trending/popular suggestions when no query is provided.
     */
    public function getPopularSuggestions(): array
    {
        $periodDays = config('search.trending_period_days', 7);
        $limit = config('search.trending_limit', 10);

        $businesses = Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->where('average_rating', '>=', 4)
            ->orderByDesc('review_count')
            ->take($limit)
            ->get()
            ->map(fn ($b) => [
                'text' => $b->name,
                'type' => 'business',
                'subtitle' => $b->city ? "{$b->category?->name} in {$b->city}" : ($b->category?->name ?? ''),
                'image' => $b->logo,
                'url' => route('business.profile', $b),
            ]);

        return $businesses->toArray();
    }

    protected function suggestBusinesses(string $query): Collection
    {
        return Business::search($query)
            ->where('is_active', true)
            ->where('is_discoverable', true)
            ->take(3)
            ->get()
            ->map(fn ($b) => [
                'text' => $b->name,
                'type' => 'business',
                'subtitle' => $b->city ? "{$b->category?->name} in {$b->city}" : ($b->category?->name ?? ''),
                'image' => $b->logo,
                'url' => route('business.profile', $b),
            ]);
    }

    protected function suggestProducts(string $query): Collection
    {
        return Product::search($query)
            ->where('is_active', true)
            ->where('is_public', true)
            ->whereHas('business', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->take(3)
            ->get()
            ->map(fn ($p) => [
                'text' => $p->name,
                'type' => 'product',
                'subtitle' => $p->business?->name ?? '',
                'image' => is_array($p->images) ? ($p->images[0] ?? null) : null,
                'url' => route('business.product', [$p->business, $p]),
            ]);
    }

    protected function suggestServices(string $query): Collection
    {
        return Service::search($query)
            ->where('is_active', true)
            ->where('is_public', true)
            ->whereHas('business', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->take(3)
            ->get()
            ->map(fn ($s) => [
                'text' => $s->name,
                'type' => 'service',
                'subtitle' => $s->business?->name ?? '',
                'image' => is_array($s->images) ? ($s->images[0] ?? null) : null,
                'url' => route('business.service', [$s->business, $s]),
            ]);
    }
}
