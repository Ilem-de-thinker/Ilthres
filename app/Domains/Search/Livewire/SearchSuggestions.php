<?php

namespace App\Domains\Search\Livewire;

use App\Domains\Search\Services\SuggestService;
use Livewire\Component;

class SearchSuggestions extends Component
{
    public string $search = '';

    public array $suggestions = [];

    public bool $showDropdown = false;

    protected SuggestService $suggestService;

    public function boot(): void
    {
        $this->suggestService = app(SuggestService::class);
    }

    public function refreshSuggestions(): void
    {
        if (strlen($this->search) < config('search.suggestion_min_chars', 2)) {
            $this->suggestions = $this->suggestService->getPopularSuggestions();
            $this->showDropdown = true;

            return;
        }

        $userLocation = session('user_location');
        $this->suggestions = $this->suggestService->suggest($this->search, $userLocation);
        $this->showDropdown = true;
    }

    public function updatedSearch(): void
    {
        $this->refreshSuggestions();
    }

    public function selectSuggestion(string $text): void
    {
        $this->search = $text;
        $this->showDropdown = false;
        $this->dispatch('search-selected', query: $text);
    }

    public function closeDropdown(): void
    {
        $this->showDropdown = false;
    }

    public function render()
    {
        return view('livewire.search.search-suggestions');
    }
}
