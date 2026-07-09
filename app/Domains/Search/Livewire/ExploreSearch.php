<?php

namespace App\Domains\Search\Livewire;

use App\Models\Business;
use App\Models\Category;
use App\Models\Product;
use App\Models\Service;
use Livewire\Component;

class ExploreSearch extends Component
{
    public string $search = '';

    public string $type = '';

    public ?int $categoryId = null;

    public int $page = 1;

    protected $queryString = [
        'search' => ['except' => ''],
        'type' => ['except' => ''],
        'categoryId' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingType(): void
    {
        $this->resetPage();
    }

    public function updatingCategoryId(): void
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

    public function render()
    {
        $businesses = collect();
        $products = collect();
        $services = collect();

        $discoverableBusinessIds = Business::where('is_active', true)
            ->where('is_discoverable', true)
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
                  ->orWhere('city', 'like', "%{$this->search}%")
                  ->orWhere('state', 'like', "%{$this->search}%");
            }))
            ->when($this->categoryId, fn ($q) => $q->where('category_id', $this->categoryId))
            ->pluck('id');

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

        $results = collect()
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
                'url' => route('business.profile', $b),
            ])))
            ->when($this->type === '' || $this->type === 'product', fn ($c) => $c->merge($products->map(fn ($p) => [
                'type' => 'product',
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'description' => $p->description,
                'image' => $p->images[0] ?? null,
                'price' => $p->price,
                'currency' => $p->currency,
                'business_name' => $p->business->name,
                'business_slug' => $p->business->slug,
                'category' => $p->category?->name,
                'url' => route('business.product', [$p->business, $p]),
            ])))
            ->when($this->type === '' || $this->type === 'service', fn ($c) => $c->merge($services->map(fn ($s) => [
                'type' => 'service',
                'id' => $s->id,
                'name' => $s->name,
                'slug' => $s->slug,
                'description' => $s->description,
                'image' => $s->images[0] ?? null,
                'price' => $s->price,
                'currency' => $s->currency,
                'duration_minutes' => $s->duration_minutes,
                'business_name' => $s->business->name,
                'business_slug' => $s->business->slug,
                'category' => $s->category?->name,
                'url' => route('business.service', [$s->business, $s]),
            ])))
            ->sortBy('name')
            ->values();

        $totalCount = $results->count();

        $perPage = 12;
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
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
