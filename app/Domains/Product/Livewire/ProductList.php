<?php

namespace App\Domains\Product\Livewire;

use App\Domains\Product\Actions\DeleteProduct;
use App\Models\Business;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public ?int $selectedBusinessId = null;
    public ?Business $business = null;

    public string $search = '';
    public ?string $statusFilter = null;
    public ?int $deleteId = null;
    public bool $showDeleteConfirm = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function mount(): void
    {
        $businesses = auth()->user()->businesses;

        if ($businesses->isEmpty()) {
            return;
        }

        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->loadBusiness($defaultId);
    }

    public function loadBusiness(int $businessId): void
    {
        $this->business = auth()->user()->businesses()->findOrFail($businessId);
        $this->selectedBusinessId = $this->business->id;
    }

    public function updatedSelectedBusinessId($value): void
    {
        if (! $value) {
            $this->business = null;
            $this->selectedBusinessId = null;
            return;
        }

        $this->loadBusiness((int) $value);
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
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

        $this->cancelDelete();
    }

    public function render()
    {
        $businesses = auth()->user()->businesses;

        $products = null;
        if ($this->business) {
            $query = Product::where('business_id', $this->business->id);

            if ($this->statusFilter === 'active') {
                $query->where('is_active', true);
            } elseif ($this->statusFilter === 'inactive') {
                $query->where('is_active', false);
            }

            if ($this->search) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                      ->orWhere('description', 'like', "%{$this->search}%");
                });
            }

            $products = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate(24);
        }

        return view('livewire.product.product-list', [
            'businesses' => $businesses,
            'products' => $products,
        ]);
    }
}
