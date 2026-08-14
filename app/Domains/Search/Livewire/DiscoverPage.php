<?php

namespace App\Domains\Search\Livewire;

use App\Domains\Search\Services\DiscoverService;
use Livewire\Component;

class DiscoverPage extends Component
{
    public array $featured = [];

    public array $trendingBusinesses = [];

    public array $trendingProducts = [];

    public array $trendingServices = [];

    public array $browseByCategory = [];

    protected DiscoverService $discoverService;

    public function boot(): void
    {
        $this->discoverService = app(DiscoverService::class);
    }

    public function mount(): void
    {
        $this->loadData();
    }

    protected function loadData(): void
    {
        $this->featured = $this->discoverService->featuredBusinesses()->toArray();
        $this->trendingBusinesses = $this->discoverService->trendingBusinesses()->toArray();
        $this->trendingProducts = $this->discoverService->trendingProducts()->toArray();
        $this->trendingServices = $this->discoverService->trendingServices()->toArray();
        $this->browseByCategory = $this->discoverService->browseByCategory()->toArray();
    }

    public function render()
    {
        return view('livewire.search.discover-page');
    }
}
