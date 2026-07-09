<?php

namespace App\Domains\Product\Livewire;

use App\Domains\Product\Actions\DeleteProduct;
use App\Domains\Product\Actions\ToggleStatus;
use App\Models\Product;
use Livewire\Component;

class ProductDetail extends Component
{
    public ?int $productId = null;
    public ?Product $product = null;
    public ?int $deleteId = null;
    public bool $showDeleteConfirm = false;
    public ?string $selectedImage = null;

    public function mount(int $productId): void
    {
        $this->productId = $productId;
        $this->product = Product::with('business')->findOrFail($productId);
        $this->authorize('view', $this->product);
        $this->selectedImage = $this->product->images[0] ?? null;
    }

    public function selectImage(string $path): void
    {
        $this->selectedImage = $path;
    }

    public function toggleActive(): void
    {
        $this->authorize('update', $this->product);

        $product = Product::findOrFail($this->productId);
        $product->update(['is_active' => !$product->is_active]);
        $this->product = $product->fresh();
    }

    public function confirmDelete(): void
    {
        $this->deleteId = $this->productId;
        $this->showDeleteConfirm = true;
    }

    public function cancelDelete(): void
    {
        $this->deleteId = null;
        $this->showDeleteConfirm = false;
    }

    public function delete(DeleteProduct $deleteProduct): void
    {
        $product = Product::findOrFail($this->deleteId);
        $this->authorize('delete', $product);

        $deleteProduct($product);

        $this->redirect(route('dashboard.products'), navigate: true);
    }

    public function render()
    {
        return view('livewire.product.product-detail');
    }
}
