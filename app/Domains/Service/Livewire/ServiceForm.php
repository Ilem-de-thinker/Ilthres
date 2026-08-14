<?php

namespace App\Domains\Service\Livewire;

use App\Domains\Media\Actions\UploadMedia as UploadMediaAction;
use App\Domains\Service\Actions\CreateService;
use App\Domains\Service\Actions\UpdateService;
use App\Domains\Service\DTOs\ServiceData;
use App\Models\Business;
use App\Models\Media;
use App\Models\PromoBanner;
use App\Models\Service;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceForm extends Component
{
    use WithFileUploads;

    public ?int $businessId = null;

    public ?Business $business = null;

    public ?int $serviceId = null;

    public ?Service $service = null;

    public string $name = '';

    public ?string $description = null;

    public ?float $price = null;

    public string $currency = 'NGN';

    public ?int $durationMinutes = null;

    public bool $isActive = true;

    public bool $isPublic = false;

    public int $sortOrder = 0;

    public array $images = [];

    public array $selectedBanners = [];

    public bool $showMediaPicker = false;

    public array $pendingImages = [];

    public $mediaUpload = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'durationMinutes' => ['nullable', 'integer', 'min:1', 'max:43200'],
            'isActive' => ['boolean'],
            'isPublic' => ['boolean'],
            'sortOrder' => ['integer', 'min:0'],
            'mediaUpload' => ['nullable', 'file', 'image', 'max:10240'],
        ];
    }

    protected $messages = [
        'name.required' => 'Enter a service name.',
        'price.required' => 'Enter a price.',
        'price.min' => 'Price must be at least 0.',
        'durationMinutes.min' => 'Duration must be at least 1 minute.',
        'durationMinutes.max' => 'Duration cannot exceed 30 days.',
        'mediaUpload.image' => 'Only image files are allowed.',
        'mediaUpload.max' => 'Image must be under 10MB.',
    ];

    public function mount(?int $serviceId = null): void
    {
        $businesses = auth()->user()->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
        $this->businessId = $this->business->id;

        if ($serviceId) {
            $this->service = Service::where('business_id', $this->business->id)->findOrFail($serviceId);
            $this->serviceId = $serviceId;
            $this->name = $this->service->name;
            $this->description = $this->service->description;
            $this->price = (float) $this->service->price;
            $this->currency = $this->service->currency;
            $this->durationMinutes = $this->service->duration_minutes;
            $this->isActive = $this->service->is_active;
            $this->isPublic = $this->service->is_public;
            $this->sortOrder = $this->service->sort_order;
            $this->images = $this->service->images ?? [];
            $this->selectedBanners = $this->service->banners()->pluck('promo_banners.id')->toArray();
        }
    }

    public function saveCreate(CreateService $action): void
    {
        $this->validate();

        $data = new ServiceData(
            businessId: $this->business->id,
            categoryId: null,
            name: $this->name,
            slug: Str::slug($this->name).'-'.Str::random(4),
            description: $this->description,
            price: (float) $this->price,
            currency: $this->currency,
            durationMinutes: $this->durationMinutes,
            images: $this->images ?: null,
            isActive: $this->isActive,
            isPublic: $this->isPublic,
            sortOrder: $this->sortOrder,
        );

        $this->authorize('create', Service::class);
        $service = $action($data);

        if ($service && count($this->selectedBanners)) {
            $service->banners()->sync($this->selectedBanners);
        }

        $this->dispatch('service-saved');
    }

    public function saveUpdate(UpdateService $action): void
    {
        $this->validate();

        $data = new ServiceData(
            businessId: $this->business->id,
            categoryId: null,
            name: $this->name,
            slug: $this->service->slug,
            description: $this->description,
            price: (float) $this->price,
            currency: $this->currency,
            durationMinutes: $this->durationMinutes,
            images: $this->images ?: null,
            isActive: $this->isActive,
            isPublic: $this->isPublic,
            sortOrder: $this->sortOrder,
        );

        $this->authorize('update', $this->service);
        $action($this->service, $data);

        $this->service->banners()->sync($this->selectedBanners);

        $this->dispatch('service-saved');
    }

    public function openMediaPicker(): void
    {
        $this->pendingImages = $this->images;
        $this->showMediaPicker = true;
    }

    public function closeMediaPicker(): void
    {
        $this->showMediaPicker = false;
        $this->pendingImages = [];
        $this->mediaUpload = null;
    }

    public function applyMediaPicker(): void
    {
        $this->images = $this->pendingImages;
        $this->closeMediaPicker();
    }

    public function toggleImage(string $path): void
    {
        $index = array_search($path, $this->pendingImages, true);
        if ($index !== false) {
            unset($this->pendingImages[$index]);
            $this->pendingImages = array_values($this->pendingImages);
        } else {
            $this->pendingImages[] = $path;
        }
    }

    public function removeImage(int $index): void
    {
        if (isset($this->images[$index])) {
            unset($this->images[$index]);
            $this->images = array_values($this->images);
        }
    }

    public function uploadMedia(UploadMediaAction $uploadMedia): void
    {
        $this->validateOnly('mediaUpload');

        if (! $this->mediaUpload) {
            return;
        }

        try {
            $media = $uploadMedia($this->business, $this->mediaUpload, 'service-images');
            $this->pendingImages[] = $media->path;
            $this->mediaUpload = null;
        } catch (\RuntimeException $e) {
            $this->addError('mediaUpload', $e->getMessage());
        }
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
        $heroBanners = $this->business
            ? PromoBanner::where('business_id', $this->business->id)
                ->hero()
                ->active()
                ->ordered()
                ->get()
            : collect();

        return view('livewire.service.service-form', [
            'mediaItems' => $this->mediaItems,
            'heroBanners' => $heroBanners,
        ]);
    }
}
