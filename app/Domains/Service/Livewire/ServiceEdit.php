<?php

namespace App\Domains\Service\Livewire;

use App\Domains\Media\Actions\UploadMedia as UploadMediaAction;
use App\Domains\Service\Actions\UpdateService;
use App\Domains\Service\DTOs\ServiceData;
use App\Models\Media;
use App\Models\Service;
use Livewire\Component;
use Livewire\WithFileUploads;

class ServiceEdit extends Component
{
    use WithFileUploads;

    public ?int $serviceId = null;
    public ?Service $service = null;

    public string $name = '';
    public ?string $description = null;
    public ?float $price = null;
    public string $currency = 'NGN';
    public ?int $durationMinutes = null;
    public bool $isActive = true;
    public int $sortOrder = 0;
    public array $images = [];

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
    ];

    public function mount(int $serviceId): void
    {
        $this->serviceId = $serviceId;
        $this->service = Service::findOrFail($serviceId);
        $this->authorize('update', $this->service);

        $this->name = $this->service->name;
        $this->description = $this->service->description;
        $this->price = (float) $this->service->price;
        $this->currency = $this->service->currency;
        $this->durationMinutes = $this->service->duration_minutes;
        $this->isActive = $this->service->is_active;
        $this->sortOrder = $this->service->sort_order;
        $this->images = $this->service->images ?? [];
    }

    public function save(UpdateService $action): void
    {
        $this->validate();

        $data = new ServiceData(
            businessId: $this->service->business_id,
            categoryId: null,
            name: $this->name,
            slug: $this->service->slug,
            description: $this->description,
            price: (float) $this->price,
            currency: $this->currency,
            durationMinutes: $this->durationMinutes,
            images: $this->images ?: null,
            isActive: $this->isActive,
            sortOrder: $this->sortOrder,
        );

        $action($this->service, $data);

        $this->redirect(route('dashboard.services.show', $this->service), navigate: true);
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
        if (! $this->mediaUpload) return;

        try {
            $media = $uploadMedia($this->service->business, $this->mediaUpload, 'service-images');
            $this->pendingImages[] = $media->path;
            $this->mediaUpload = null;
        } catch (\RuntimeException $e) {
            $this->addError('mediaUpload', $e->getMessage());
        }
    }

    public function getMediaItemsProperty()
    {
        return Media::where('business_id', $this->service->business_id)
            ->where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.service.service-edit', ['mediaItems' => $this->mediaItems]);
    }
}
