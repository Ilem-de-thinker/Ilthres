<?php

namespace App\Domains\Promotion\Livewire;

use App\Models\Business;
use App\Models\Promotion;
use Livewire\Component;
use Livewire\WithPagination;

class PromotionList extends Component
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

    public function delete(): void
    {
        $promotion = Promotion::findOrFail($this->deleteId);

        if ($promotion->business_id !== $this->business?->id) {
            return;
        }

        $promotion->update(['status' => 'archived']);
        $this->cancelDelete();
    }

    public function toggleStatus(int $id): void
    {
        $promotion = Promotion::findOrFail($id);

        if ($promotion->business_id !== $this->business?->id) {
            return;
        }

        $promotion->update([
            'status' => $promotion->status === 'active' ? 'paused' : 'active',
        ]);
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.promotion.promotion-list', ['hasBusiness' => false]);
        }

        $promotions = Promotion::where('business_id', $this->business->id)
            ->withCount('products', 'services')
            ->latest()
            ->paginate(10);

        return view('livewire.promotion.promotion-list', [
            'hasBusiness' => true,
            'promotions' => $promotions,
        ]);
    }
}
