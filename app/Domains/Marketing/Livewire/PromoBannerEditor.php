<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreatePromoBanner;
use App\Domains\Marketing\Actions\UpdatePromoBanner;
use App\Models\Business;
use App\Models\PromoBanner;
use Livewire\Component;

class PromoBannerEditor extends Component
{
    public ?Business $business = null;
    public ?PromoBanner $editingBanner = null;
    public string $message = '';
    public string $position = 'top';
    public string $style = 'announcement';
    public ?string $ctaText = null;
    public ?string $ctaUrl = null;
    public ?string $startsAt = null;
    public ?string $endsAt = null;
    public bool $isActive = true;
    public ?int $editId = null;

    protected function rules(): array
    {
        return [
            'message' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:top,bottom,hero'],
            'style' => ['required', 'in:announcement,sale,seasonal'],
            'ctaText' => ['nullable', 'string', 'max:100'],
            'ctaUrl' => ['nullable', 'url', 'max:500'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
            'isActive' => ['boolean'],
        ];
    }

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function edit(PromoBanner $banner): void
    {
        $this->editId = $banner->id;
        $this->editingBanner = $banner;
        $this->message = $banner->message;
        $this->position = $banner->position;
        $this->style = $banner->style;
        $this->ctaText = $banner->cta_text;
        $this->ctaUrl = $banner->cta_url;
        $this->startsAt = $banner->starts_at?->format('Y-m-d');
        $this->endsAt = $banner->ends_at?->format('Y-m-d');
        $this->isActive = $banner->is_active;
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->editingBanner = null;
        $this->message = '';
        $this->position = 'top';
        $this->style = 'announcement';
        $this->ctaText = null;
        $this->ctaUrl = null;
        $this->startsAt = null;
        $this->endsAt = null;
        $this->isActive = true;
    }

    public function save(CreatePromoBanner $createAction, UpdatePromoBanner $updateAction): void
    {
        $this->validate();
        if (! $this->business) { return; }

        $data = [
            'business_id' => $this->business->id,
            'message' => $this->message,
            'position' => $this->position,
            'style' => $this->style,
            'cta_text' => $this->ctaText,
            'cta_url' => $this->ctaUrl,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'is_active' => $this->isActive,
        ];

        if ($this->editId && $this->editingBanner) {
            $updateAction($this->editingBanner, $data);
        } else {
            $createAction($data);
        }

        $this->resetForm();
        $this->dispatch('banner-saved');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.promo-banner-editor', ['hasBusiness' => false]);
        }
        $banners = PromoBanner::where('business_id', $this->business->id)->latest()->get();
        return view('livewire.marketing.promo-banner-editor', [
            'hasBusiness' => true,
            'banners' => $banners,
        ]);
    }
}
