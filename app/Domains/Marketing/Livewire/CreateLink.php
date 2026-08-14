<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateLink as CreateLinkAction;
use App\Domains\Marketing\DTOs\MarketingLinkData;
use App\Models\Business;
use Illuminate\Support\Str;
use Livewire\Component;

class CreateLink extends Component
{
    public ?Business $business = null;

    public string $name = '';

    public string $slug = '';

    public string $url = '';

    public ?string $description = null;

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function updatedName(): void
    {
        $this->slug = Str::slug($this->name);
    }

    public function save(): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:marketing_links,slug'],
            'url' => ['required', 'url', 'max:2000'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        app(CreateLinkAction::class)(new MarketingLinkData(
            businessId: $this->business->id,
            name: $this->name,
            slug: $this->slug,
            url: $this->url,
            description: $this->description,
        ));

        session()->flash('success', 'Marketing link created.');

        $this->redirectRoute('dashboard.marketing');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.create-link', [
                'hasBusiness' => false,
            ]);
        }

        return view('livewire.marketing.create-link', [
            'hasBusiness' => true,
        ]);
    }
}
