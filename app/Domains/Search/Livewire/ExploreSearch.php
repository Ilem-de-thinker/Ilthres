<?php

namespace App\Domains\Search\Livewire;

use App\Domains\Search\Services\AISearchService;
use App\Domains\Search\Services\RankingService;
use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class ExploreSearch extends Component
{
    public string $search = '';

    public string $type = '';

    public ?int $categoryId = null;

    public bool $verified = false;

    public bool $openNow = false;

    public string $sort = 'relevance';

    public int $page = 1;

    public bool $useAISearch = false;

    public ?array $userLocation = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'verified' => ['except' => false],
        'openNow' => ['except' => false],
        'sort' => ['except' => 'relevance'],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(string $value): void
    {
        $this->resetPage();
        $this->determineSearchMode($value);
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
    {
        $this->resetPage();
    }

    public function updatingVerified(): void
    {
        $this->resetPage();
    }

    public function updatingOpenNow(): void
    {
        $this->resetPage();
    }

    public function updatingSort(): void
    {
        $this->resetPage();
    }

    public function nextPage(): void
    {
        $this->page++;
    }

    public function previousPage(): void
    {
        $this->page = max(1, $this->page - 1);
    }

    public function resetPage(): void
    {
        $this->page = 1;
    }

    public function storeUserLocation(float $lat, float $lng): void
    {
        $this->userLocation = ['lat' => $lat, 'lng' => $lng];
        session(['user_location' => $this->userLocation]);
    }

    protected function determineSearchMode(string $value): void
    {
        $query = strtolower(trim($value));

        $nlIndicators = [
            'near me', 'i need', 'i want', 'find me', 'looking for',
            'where can i', 'best ', 'cheap ', 'affordable ', 'top ',
            'show me', 'help me find', 'where is', 'who has',
        ];

        $this->useAISearch = false;
        foreach ($nlIndicators as $indicator) {
            if (str_contains($query, $indicator)) {
                $this->useAISearch = true;
                break;
            }
        }

        if (strlen($query) > 25) {
            $this->useAISearch = true;
        }
    }

    public function render()
    {
        if (strlen(trim($this->search)) > 1 && $this->useAISearch) {
            $results = $this->aiSearch();
        } elseif (strlen(trim($this->search)) > 0 || $this->type || $this->categoryId || $this->verified || $this->openNow) {
            $results = $this->traditionalSearch();
        } else {
            $results = $this->browseResults();
        }

        $results = $this->applySorting($results);

        $totalCount = $results->count();

        $perPage = config('search.results_per_page', 12);
        $page = max(1, $this->page);
        $paginated = $results->forPage($page, $perPage);
        $hasMore = $page * $perPage < $totalCount;
        $lastPage = max(1, (int) ceil($totalCount / $perPage));

        return view('livewire.search.explore-search', [
            'results' => $paginated,
            'totalCount' => $totalCount,
            'page' => $page,
            'perPage' => $perPage,
            'hasMore' => $hasMore,
            'lastPage' => $lastPage,
            'categories' => Cache::remember('active_categories', 3600, fn () => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get()),
        ]);
    }

    protected function aiSearch(): Collection
    {
        $aiSearch = app(AISearchService::class);
        $userLocation = $this->userLocation ?? session('user_location');
        $rankedBusinesses = $aiSearch->search($this->search, $userLocation);

        $results = collect();

        foreach ($rankedBusinesses as $business) {
            $results->push([
                'type' => 'business',
                'id' => $business->id,
                'name' => $business->name,
                'slug' => $business->slug,
                'description' => $business->description,
                'image' => $business->logo,
                'city' => $business->city,
                'state' => $business->state,
                'category' => $business->category?->name,
                'verified' => $business->is_verified ?? false,
                'rating' => $business->average_rating ?? null,
                'review_count' => $business->review_count ?? 0,
                'url' => route('business.profile', $business),
                '_created_at' => $business->created_at,
            ]);

            if ($this->type === '' || $this->type === 'product') {
                foreach ($business->products()->where('is_active', true)->where('is_public', true)->take(3)->get() as $product) {
                    $results->push([
                        'type' => 'product',
                        'id' => $product->id,
                        'name' => $product->name,
                        'slug' => $product->slug,
                        'description' => $product->description,
                        'image' => is_array($product->images) ? ($product->images[0] ?? null) : null,
                        'price' => $product->price,
                        'currency' => $product->currency,
                        'business_name' => $business->name,
                        'business_slug' => $business->slug,
                        'category' => $product->category?->name,
                        'url' => route('business.product', [$business, $product]),
                        '_created_at' => $product->created_at,
                    ]);
                }
            }

            if ($this->type === '' || $this->type === 'service') {
                foreach ($business->services()->where('is_active', true)->where('is_public', true)->take(3)->get() as $service) {
                    $results->push([
                        'type' => 'service',
                        'id' => $service->id,
                        'name' => $service->name,
                        'slug' => $service->slug,
                        'description' => $service->description,
                        'image' => is_array($service->images) ? ($service->images[0] ?? null) : null,
                        'price' => $service->price,
                        'currency' => $service->currency,
                        'duration_minutes' => $service->duration_minutes,
                        'business_name' => $business->name,
                        'business_slug' => $business->slug,
                        'category' => $service->category?->name,
                        'url' => route('business.service', [$business, $service]),
                        '_created_at' => $service->created_at,
                    ]);
                }
            }
        }

        if ($this->categoryId) {
            $results = $results->filter(fn ($r) => $r['category'] === Category::find($this->categoryId)?->name);
        }

        if ($this->verified) {
            $results = $results->filter(fn ($r) => ($r['type'] === 'business' && $r['verified']) || $r['type'] !== 'business');
        }

        return $results->values();
    }

    protected function traditionalSearch(): Collection
    {
        $discoverableBusinessIds = Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhere('city', 'like', "%{$this->search}%")
                    ->orWhere('state', 'like', "%{$this->search}%");
            }))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->when($this->verified, fn ($q) => $q->where('is_verified', true))
            ->pluck('id');

        $businesses = collect();
        $products = collect();
        $services = collect();

        if ($this->type === '' || $this->type === 'business') {
            $businesses = Business::whereIn('id', $discoverableBusinessIds)
                ->where('is_active', true)
                ->where('is_discoverable', true)
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%")
                        ->orWhere('city', 'like', "%{$this->search}%")
                        ->orWhere('state', 'like', "%{$this->search}%");
                }))
                ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
                ->orderBy('name')
                ->get();
        }

        if ($this->type === '' || $this->type === 'product') {
            $products = Product::whereIn('business_id', $discoverableBusinessIds)
                ->where('is_active', true)
                ->where('is_public', true)
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                }))
                ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
                ->with('business')
                ->orderBy('name')
                ->get();
        }

        if ($this->type === '' || $this->type === 'service') {
            $services = Service::whereIn('business_id', $discoverableBusinessIds)
                ->where('is_active', true)
                ->where('is_public', true)
                ->when($this->search, fn ($q) => $q->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                }))
                ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
                ->with('business')
                ->orderBy('name')
                ->get();
        }

        $ranking = app(RankingService::class);
        $userLocation = $this->userLocation ?? session('user_location');

        return collect()
            ->when($this->type === '' || $this->type === 'business', fn ($c) => $c->merge($businesses->map(fn ($b) => [
                'type' => 'business',
                'id' => $b->id,
                'name' => $b->name,
                'slug' => $b->slug,
                'description' => $b->description,
                'image' => $b->logo,
                'city' => $b->city,
                'state' => $b->state,
                'category' => $b->category?->name,
                'verified' => $b->is_verified ?? false,
                'rating' => $b->average_rating ?? null,
                'review_count' => $b->review_count ?? 0,
                'url' => route('business.profile', $b),
                '_score' => $ranking->calculateScore($b, $userLocation),
                '_created_at' => $b->created_at,
            ])))
            ->when($this->type === '' || $this->type === 'product', fn ($c) => $c->merge($products->map(fn ($p) => [
                'type' => 'product',
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->description,
                'image' => is_array($p->images) ? ($p->images[0] ?? null) : null,
                'price' => $p->price,
                'currency' => $p->currency,
                'business_name' => $p->business->name,
                'business_slug' => $p->business->slug,
                'category' => $p->category?->name,
                'url' => route('business.product', [$p->business, $p]),
                '_score' => $ranking->calculateScore($p->business, $userLocation),
                '_created_at' => $p->created_at,
            ])))
            ->when($this->type === '' || $this->type === 'service', fn ($c) => $c->merge($services->map(fn ($s) => [
                'type' => 'service',
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'description' => $s->description,
                'image' => is_array($s->images) ? ($s->images[0] ?? null) : null,
                'price' => $s->price,
                'currency' => $s->currency,
                'duration_minutes' => $s->duration_minutes,
                'business_name' => $s->business->name,
                'business_slug' => $s->business->slug,
                'category' => $s->category?->name,
                'url' => route('business.service', [$s->business, $s]),
                '_score' => $ranking->calculateScore($s->business, $userLocation),
                '_created_at' => $s->created_at,
            ])))
            ->sortByDesc('_score')
            ->values();
    }

    protected function browseResults(): Collection
    {
        $results = collect();

        $topBusinesses = Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->orderByDesc('average_rating')
            ->orderByDesc('review_count')
            ->take(12)
            ->get();

        foreach ($topBusinesses as $business) {
            $results->push([
                'type' => 'business',
                'id' => $business->id,
                'name' => $business->name,
                'slug' => $business->slug,
                'description' => $business->description,
                'image' => $business->logo,
                'city' => $business->city,
                'state' => $business->state,
                'category' => $business->category?->name,
                'verified' => $business->is_verified ?? false,
                'rating' => $business->average_rating ?? null,
                'review_count' => $business->review_count ?? 0,
                'url' => route('business.profile', $business),
                '_score' => $business->average_rating ?? 0,
                '_created_at' => $business->created_at,
            ]);
        }

        return $results;
    }

    protected function applySorting(Collection $results): Collection
    {
        return match ($this->sort) {
            'newest' => $results->sortByDesc('_created_at')->values(),
            'rating' => $results->sortByDesc('rating')->values(),
            'popular' => $results->sortByDesc('review_count')->values(),
            default => $results->sortByDesc('_score')->values(),
        };
    }
}
