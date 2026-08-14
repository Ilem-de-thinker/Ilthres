<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreatePromoBanner;
use App\Domains\Marketing\Actions\UpdatePromoBanner;
use App\Models\Business;
use App\Models\Media;
use App\Models\Product;
use App\Models\PromoBanner;
use App\Models\Service;
use Livewire\Component;

class PromoBannerEditor extends Component
{
    public ?Business $business = null;

    public ?PromoBanner $editingBanner = null;

    public string $message = '';

    public string $position = 'hero';

    public ?string $style = null;

    public int $sortOrder = 0;

    public ?string $ctaText = null;

    public ?string $ctaUrl = null;

    public ?string $startsAt = null;

    public ?string $endsAt = null;

    public bool $isActive = true;

    public ?int $editId = null;

    public ?string $image = null;

    public bool $showImagePicker = false;

    public array $selectedProducts = [];

    public array $selectedServices = [];

    public string $searchQuery = '';

    public array $searchResults = [];

    public bool $showLinkPicker = false;

    protected function rules(): array
    {
        $rules = [
            'message' => ['required', 'string', 'max:255'],
            'position' => ['required', 'in:top,bottom,hero'],
            'sortOrder' => ['integer', 'min:0'],
            'ctaText' => ['nullable', 'string', 'max:100'],
            'ctaUrl' => ['nullable', 'url', 'max:500'],
            'startsAt' => ['nullable', 'date'],
            'endsAt' => ['nullable', 'date', 'after_or_equal:startsAt'],
            'isActive' => ['boolean'],
            'image' => ['nullable', 'string'],
        ];

        if ($this->position === 'hero') {
            $rules['style'] = ['nullable', 'in:announcement,sale,seasonal'];
        } else {
            $rules['style'] = ['required', 'in:announcement,sale,seasonal'];
        }

        return $rules;
    }

    protected $listeners = ['banner-image-selected'];

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

    public function edit(PromoBanner $banner): void
    {
        $this->editId = $banner->id;
        $this->editingBanner = $banner;
        $this->message = $banner->message;
        $this->position = $banner->position;
        $this->style = $banner->style;
        $this->sortOrder = $banner->sort_order;
        $this->ctaText = $banner->cta_text;
        $this->ctaUrl = $banner->cta_url;
        $this->startsAt = $banner->starts_at?->format('Y-m-d');
        $this->endsAt = $banner->ends_at?->format('Y-m-d');
        $this->isActive = $banner->is_active;
        $this->image = $banner->image;
        $this->selectedProducts = $banner->products()->pluck('products.id')->toArray();
        $this->selectedServices = $banner->services()->pluck('services.id')->toArray();
    }

    public function resetForm(): void
    {
        $this->editId = null;
        $this->editingBanner = null;
        $this->message = '';
        $this->position = 'hero';
        $this->style = null;
        $this->sortOrder = 0;
        $this->ctaText = null;
        $this->ctaUrl = null;
        $this->startsAt = null;
        $this->endsAt = null;
        $this->isActive = true;
        $this->image = null;
        $this->selectedProducts = [];
        $this->selectedServices = [];
    }

    public function openImagePicker(): void
    {
        $this->showImagePicker = true;
    }

    public function closeImagePicker(): void
    {
        $this->showImagePicker = false;
    }

    public function bannerImageSelected(string $path): void
    {
        $this->image = $path;
        $this->closeImagePicker();
    }

    public function removeImage(): void
    {
        $this->image = null;
    }

    public function openLinkPicker(): void
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->showLinkPicker = true;
    }

    public function closeLinkPicker(): void
    {
        $this->showLinkPicker = false;
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updatedPosition(): void
    {
        if ($this->position === 'hero') {
            $this->style = null;
        } elseif ($this->style === null) {
            $this->style = 'announcement';
        }
    }

    public function updatedSearchQuery(): void
    {
        if (strlen($this->searchQuery) < 2 || ! $this->business) {
            $this->searchResults = [];

            return;
        }

        $query = $this->searchQuery;
        $results = [];

        if ($this->business->type === 'product_store') {
            $results = Product::where('business_id', $this->business->id)
                ->where('name', 'like', "%{$query}%")
                ->whereNotIn('id', $this->selectedProducts)
                ->limit(10)
                ->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->name, 'type' => 'product', 'image' => $p->images[0] ?? null])
                ->toArray();
        } else {
            $results = Service::where('business_id', $this->business->id)
                ->where('name', 'like', "%{$query}%")
                ->whereNotIn('id', $this->selectedServices)
                ->limit(10)
                ->get()
                ->map(fn ($s) => ['id' => $s->id, 'name' => $s->name, 'type' => 'service', 'image' => $s->images[0] ?? null])
                ->toArray();
        }

        $this->searchResults = $results;
    }

    public function addProduct(int $id): void
    {
        if (! in_array($id, $this->selectedProducts)) {
            $this->selectedProducts[] = $id;
        }
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function removeProduct(int $id): void
    {
        $this->selectedProducts = array_values(array_diff($this->selectedProducts, [$id]));
    }

    public function addService(int $id): void
    {
        if (! in_array($id, $this->selectedServices)) {
            $this->selectedServices[] = $id;
        }
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function removeService(int $id): void
    {
        $this->selectedServices = array_values(array_diff($this->selectedServices, [$id]));
    }

    public function save(CreatePromoBanner $createAction, UpdatePromoBanner $updateAction): void
    {
        $this->validate();
        if (! $this->business) {
            return;
        }

        $data = [
            'business_id' => $this->business->id,
            'message' => $this->message,
            'image' => $this->image,
            'position' => $this->position,
            'style' => $this->style,
            'sort_order' => $this->sortOrder,
            'cta_text' => $this->ctaText,
            'cta_url' => $this->ctaUrl,
            'starts_at' => $this->startsAt,
            'ends_at' => $this->endsAt,
            'is_active' => $this->isActive,
        ];

        if ($this->editId && $this->editingBanner) {
            $banner = $updateAction($this->editingBanner, $data);
        } else {
            $banner = $createAction($data);
        }

        if ($this->business->type === 'product_store') {
            $banner->products()->sync($this->selectedProducts);
        } else {
            $banner->services()->sync($this->selectedServices);
        }

        $this->resetForm();
        $this->dispatch('banner-saved');
    }

    public function getMediaItemsProperty()
    {
        if (! $this->business) {
            return collect();
        }

        return Media::where('business_id', $this->business->id)
            ->where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.promo-banner-editor', ['hasBusiness' => false]);
        }

        $banners = PromoBanner::where('business_id', $this->business->id)
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        $selectedProductsData = Product::whereIn('id', $this->selectedProducts)->get();
        $selectedServicesData = Service::whereIn('id', $this->selectedServices)->get();

        return view('livewire.marketing.promo-banner-editor', [
            'hasBusiness' => true,
            'banners' => $banners,
            'mediaItems' => $this->mediaItems,
            'selectedProductsData' => $selectedProductsData,
            'selectedServicesData' => $selectedServicesData,
        ]);
    }
}
