<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\DisableDiscount;
use App\Models\Business;
use App\Models\Discount;
use Livewire\Component;
use Livewire\WithPagination;

class DiscountList extends Component
{
    use WithPagination;

    public ?Business $business = null;

    public ?int $deleteId = null;

    public bool $showDeleteConfirm = false;

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

    public function delete(DisableDiscount $action): void
    {
        $discount = Discount::findOrFail($this->deleteId);
        $action($discount);
        $this->cancelDelete();
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.discount-list', ['hasBusiness' => false]);
        }

        $discounts = Discount::where('business_id', $this->business->id)
            ->withCount('redemptions')
            ->latest()
            ->paginate(10);

        return view('livewire.marketing.discount-list', [
            'hasBusiness' => true,
            'discounts' => $discounts,
        ]);
    }
}
