<?php

namespace App\Domains\Search\Services;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;

class DiscoverService
{
    protected int $trendingPeriodDays;

    protected int $trendingLimit;

    public function __construct()
    {
        $this->trendingPeriodDays = config('search.trending_period_days', 7);
        $this->trendingLimit = config('search.trending_limit', 10);
    }

    /**
     * Get trending businesses based on recent activity and ratings.
     */
    public function trendingBusinesses(): Collection
    {
        return Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->where('average_rating', '>=', 3)
            ->orderByDesc('average_rating')
            ->orderByDesc('review_count')
            ->take($this->trendingLimit)
            ->get();
    }

    /**
     * Get trending products based on recent activity.
     */
    public function trendingProducts(): Collection
    {
        return Product::where('is_active', true)
            ->where('is_public', true)
            ->whereHas('business', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('created_at')
            ->take($this->trendingLimit)
            ->with('business')
            ->get();
    }

    /**
     * Get trending services.
     */
    public function trendingServices(): Collection
    {
        return Service::where('is_active', true)
            ->where('is_public', true)
            ->whereHas('business', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->orderByDesc('created_at')
            ->take($this->trendingLimit)
            ->with('business')
            ->get();
    }

    /**
     * Get related businesses (same category or city).
     */
    public function relatedBusinesses(Business $business, int $limit = 6): Collection
    {
        return Business::where('id', '!=', $business->id)
            ->where('is_active', true)
            ->where('is_discoverable', true)
            ->where(function ($q) use ($business) {
                $q->where('category_id', $business->category_id)
                    ->orWhere('city', $business->city);
            })
            ->orderByDesc('average_rating')
            ->take($limit)
            ->get();
    }

    /**
     * Get related products (same category).
     */
    public function relatedProducts(Product $product, int $limit = 6): Collection
    {
        return Product::where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('is_public', true)
            ->where('category_id', $product->category_id)
            ->whereHas('business', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->with('business')
            ->take($limit)
            ->get();
    }

    /**
     * Get featured/spotlight businesses (highest rated, verified).
     */
    public function featuredBusinesses(int $limit = 6): Collection
    {
        return Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->where('is_verified', true)
            ->where('average_rating', '>=', 4)
            ->orderByDesc('search_boost')
            ->orderByDesc('average_rating')
            ->orderByDesc('review_count')
            ->take($limit)
            ->get();
    }

    /**
     * Get category browse data — top businesses per category.
     */
    public function browseByCategory(int $limit = 3): Collection
    {
        $categories = Category::where('is_active', true)
            ->with(['businesses' => function ($q) use ($limit) {
                $q->where('is_active', true)
                    ->where('is_discoverable', true)
                    ->orderByDesc('average_rating')
                    ->take($limit);
            }])
            ->whereHas('businesses', function ($q) {
                $q->where('is_active', true)->where('is_discoverable', true);
            })
            ->orderBy('sort_order')
            ->get();

        return $categories->filter(fn ($c) => $c->businesses->isNotEmpty());
    }
}
