<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Business;
use App\Models\Plan;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Admin business management list.
 *
 * Displays all businesses with their status. Admins can approve,
 * suspend, or activate businesses from this panel.
 */
#[Layout('layouts.admin')]
class BusinessManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $filterType = '';

    public ?int $changingPlanFor = null;

    public ?int $selectedPlanId = null;

    /**
     * Toggle the active status of a business.
     */
    public function toggleActive(int $businessId): void
    {
        $business = Business::withTrashed()->findOrFail($businessId);
        $business->update(['is_active' => ! $business->is_active]);

        $status = $business->is_active ? 'activated' : 'suspended';
        session()->flash('success', "Business {$business->name} has been {$status}.");
    }

    /**
     * Permanently delete a business.
     */
    public function deleteBusiness(int $businessId): void
    {
        $business = Business::withTrashed()->findOrFail($businessId);
        $business->forceDelete();

        session()->flash('success', 'Business permanently deleted.');
    }

    /**
     * Show the plan change dropdown for a given business.
     */
    public function startPlanChange(int $businessId): void
    {
        $this->changingPlanFor = $businessId;

        $business = Business::withTrashed()->findOrFail($businessId);
        $this->selectedPlanId = $business->subscription?->plan_id;
    }

    /**
     * Cancel the plan change without saving.
     */
    public function cancelPlanChange(): void
    {
        $this->changingPlanFor = null;
        $this->selectedPlanId = null;
    }

    /**
     * Change the subscription plan for a business.
     */
    public function changePlan(int $businessId): void
    {
        $business = Business::withTrashed()->findOrFail($businessId);

        if (! $this->selectedPlanId) {
            session()->flash('error', 'Please select a plan.');

            return;
        }

        $plan = Plan::find($this->selectedPlanId);
        if (! $plan) {
            session()->flash('error', 'Selected plan not found.');

            return;
        }

        if (! $business->subscription) {
            session()->flash('error', 'This business does not have a subscription.');

            return;
        }

        $oldPlan = $business->subscription->plan?->name ?? 'None';
        $business->subscription->update([
            'plan_id' => $plan->id,
            'price_paid' => (float) $plan->price,
        ]);

        $this->changingPlanFor = null;
        $this->selectedPlanId = null;

        session()->flash('success', "{$business->name} plan changed from {$oldPlan} to {$plan->name}.");
    }

    public function render()
    {
        $businesses = Business::query()
            ->with(['user', 'subscription.plan'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus === 'active', fn ($q) => $q->where('is_active', true))
            ->when($this->filterStatus === 'suspended', fn ($q) => $q->where('is_active', false))
            ->when($this->filterStatus === 'deleted', fn ($q) => $q->onlyTrashed())
            ->when($this->filterType === 'linked', fn ($q) => $q->whereNotNull('website_url'))
            ->when($this->filterType === 'hosted', fn ($q) => $q->whereNull('website_url'))
            ->latest()
            ->paginate(20);

        return view('livewire.administration.business-manager', [
            'businesses' => $businesses,
            'plans' => Plan::where('is_active', true)->orderBy('sort_order')->get(),
        ]);
    }
}
