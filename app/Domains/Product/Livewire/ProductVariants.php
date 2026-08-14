<?php

namespace App\Domains\Product\Livewire;

use App\Domains\Product\Actions\UpdateProduct;
use App\Domains\Product\DTOs\ProductData;
use App\Models\Media;
use App\Models\Product;
use Livewire\Component;

class ProductVariants extends Component
{
    public ?int $productId = null;

    public ?Product $product = null;

    public array $variants = [];

    public bool $saved = false;

    public ?int $pickerVariantIndex = null;

    public ?int $pickerOptionIndex = null;

    public bool $showImagePicker = false;

    protected $listeners = ['variant-image-selected'];

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->product = Product::findOrFail($productId);
        $this->authorize('update', $this->product);
        $this->variants = collect($this->product->variants ?? [])->map(function ($group) {
            $group['options'] = collect($group['options'] ?? [])->map(function ($option) {
                return is_string($option) ? ['value' => $option, 'image' => null] : $option;
            })->values()->toArray();

            return $group;
        })->values()->toArray();
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

    public function addOption(int $variantIndex): void
    {
        $this->variants[$variantIndex]['options'][] = ['value' => '', 'image' => null];
    }

    public function removeOption(int $variantIndex, int $optionIndex): void
    {
        if (isset($this->variants[$variantIndex]['options'][$optionIndex])) {
            unset($this->variants[$variantIndex]['options'][$optionIndex]);
            $this->variants[$variantIndex]['options'] = array_values($this->variants[$variantIndex]['options']);
        }
    }

    public function openImagePicker(int $variantIndex, int $optionIndex): void
    {
        $this->pickerVariantIndex = $variantIndex;
        $this->pickerOptionIndex = $optionIndex;
        $this->showImagePicker = true;
    }

    public function closeImagePicker(): void
    {
        $this->pickerVariantIndex = null;
        $this->pickerOptionIndex = null;
        $this->showImagePicker = false;
    }

    public function setOptionImage(string $path): void
    {
        if ($this->pickerVariantIndex !== null && $this->pickerOptionIndex !== null) {
            $this->variants[$this->pickerVariantIndex]['options'][$this->pickerOptionIndex]['image'] = $path;
        }
        $this->closeImagePicker();
    }

    public function removeOptionImage(int $variantIndex, int $optionIndex): void
    {
        $this->variants[$variantIndex]['options'][$optionIndex]['image'] = null;
    }

    public function save(UpdateProduct $action): void
    {
        $data = new ProductData(
            businessId: $this->product->business_id,
            categoryId: $this->product->category_id,
            name: $this->product->name,
            slug: $this->product->slug,
            description: $this->product->description,
            price: (float) $this->product->price,
            comparePrice: $this->product->compare_price ? (float) $this->product->compare_price : null,
            currency: $this->product->currency,
            images: $this->product->images,
            variants: $this->variants ?: null,
            isActive: $this->product->is_active,
            isFeatured: $this->product->is_featured,
            sortOrder: $this->product->sort_order,
        );

        $action($this->product, $data);

        $this->saved = true;
    }

    public function getMediaItemsProperty()
    {
        return Media::where('business_id', $this->product->business_id)
            ->where('mime_type', 'like', 'image/%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.product.product-variants', [
            'mediaItems' => $this->mediaItems,
        ]);
    }
}
