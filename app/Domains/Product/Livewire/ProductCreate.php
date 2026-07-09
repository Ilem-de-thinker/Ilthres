<?php

namespace App\Domains\Product\Livewire;

use App\Domains\Media\Actions\UploadMedia as UploadMediaAction;
use App\Domains\Product\Actions\CreateProduct;
use App\Domains\Product\DTOs\ProductData;
use App\Models\Business;
use App\Models\Media;
use App\Models\Product;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductCreate extends Component
{
    use WithFileUploads;

    public ?Business $business = null;

    public string $name = '';
    public ?string $description = null;
    public ?float $price = null;
    public ?float $comparePrice = null;
    public string $currency = 'NGN';
    public bool $isActive = true;
    public bool $isFeatured = false;
    public int $sortOrder = 0;
    public array $images = [];
    public array $variants = [];

    public bool $showMediaPicker = false;
    public array $pendingImages = [];
    public $mediaUpload = null;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'price' => ['required', 'numeric', 'min:0'],
            'comparePrice' => ['nullable', 'numeric', 'min:0', 'gt:price'],
            'currency' => ['required', 'string', 'size:3'],
            'isActive' => ['boolean'],
            'isFeatured' => ['boolean'],
            'sortOrder' => ['integer', 'min:0'],
            'mediaUpload' => ['nullable', 'file', 'image', 'max:10240'],
        ];
    }

    protected $messages = [
        'name.required' => 'Enter a product name.',
        'price.required' => 'Enter a price.',
        'price.min' => 'Price must be at least 0.',
        'comparePrice.gt' => 'Compare price must be greater than the selling price.',
    ];

    public function mount(): void
    {
        $defaultId = session('current_business_id');
        $this->business = auth()->user()->businesses()->find($defaultId) ?? auth()->user()->businesses()->first();
    }

    public function save(CreateProduct $action): void
    {
        $this->validate();

        $data = new ProductData(
            businessId: $this->business->id,
            categoryId: null,
            name: $this->name,
            slug: Str::slug($this->name) . '-' . Str::random(4),
            description: $this->description,
            price: (float) $this->price,
            comparePrice: $this->comparePrice ? (float) $this->comparePrice : null,
            currency: $this->currency,
            images: $this->images ?: null,
            variants: $this->variants ?: null,
            isActive: $this->isActive,
            isFeatured: $this->isFeatured,
            sortOrder: $this->sortOrder,
        );

        $this->authorize('create', Product::class);
        $product = $action($data);

        $this->redirect(route('dashboard.products.show', $product), navigate: true);
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
            $media = $uploadMedia($this->business, $this->mediaUpload, 'product-images');
            $this->pendingImages[] = $media->path;
            $this->mediaUpload = null;
        } catch (\RuntimeException $e) {
            $this->addError('mediaUpload', $e->getMessage());
        }
    }

    public function addVariant(): void
    {
        $this->variants[] = ['name' => '', 'options' => []];
    }

    public function removeVariant(int $index): void
    {
        if (isset($this->variants[$index])) {
            unset($this->variants[$index]);
            $this->variants = array_values($this->variants);
        }
    }

    public function addVariantOption(int $variantIndex): void
    {
        $this->variants[$variantIndex]['options'][] = ['value' => '', 'image' => null];
    }

    public function removeVariantOption(int $variantIndex, int $optionIndex): void
    {
        if (isset($this->variants[$variantIndex]['options'][$optionIndex])) {
            unset($this->variants[$variantIndex]['options'][$optionIndex]);
            $this->variants[$variantIndex]['options'] = array_values($this->variants[$variantIndex]['options']);
        }
    }

    public function setVariantOptionImage(int $variantIndex, int $optionIndex, string $path): void
    {
        $this->variants[$variantIndex]['options'][$optionIndex]['image'] = $path;
    }

    public function getMediaItemsProperty()
    {
        if (! $this->business) return collect();
        return Media::where('business_id', $this->business->id)
            ->where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.product.product-create', ['mediaItems' => $this->mediaItems]);
    }
}
