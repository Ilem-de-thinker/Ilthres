<?php

namespace App\Domains\Subscription\Livewire;

use App\Models\Business;
use App\Models\Plan;
use Livewire\Component;

class SubscriptionStatus extends Component
{
    public ?Business $business = null;

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

    public function render()
    {
        if (! $this->business) {
            return view('livewire.subscription.subscription-status', [
                'hasBusiness' => false,
            ]);
        }

        $subscription = $this->business->subscription;
        $plan = $subscription?->plan;
        $allPlans = Plan::where('is_active', true)->orderBy('sort_order')->get();

        $upgradePlan = null;
        $downgradePlan = null;
        if ($plan) {
            $upgradePlan = $allPlans->firstWhere('sort_order', '>', $plan->sort_order);
            $downgradePlan = $allPlans->last(fn ($p) => $p->sort_order < $plan->sort_order);
        }

        $limits = $plan ? config("plans.{$plan->slug}", []) : [];
        $scheduledPlan = $subscription?->scheduledPlan;

        return view('livewire.subscription.subscription-status', [
            'hasBusiness' => true,
            'subscription' => $subscription,
            'plan' => $plan,
            'upgradePlan' => $upgradePlan,
            'downgradePlan' => $downgradePlan,
            'limits' => $limits,
            'allPlans' => $allPlans,
            'scheduledPlan' => $scheduledPlan,
        ]);
    }
}
