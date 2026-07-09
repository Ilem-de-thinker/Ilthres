<?php

namespace App\Domains\Subscription\Livewire;

use App\Models\Plan;
use HopekellDev\Paystack\Paystack;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class PricingPage extends Component
{
    public function selectPlan(int $planId): void
    {
        $plan = Plan::findOrFail($planId);
        $user = auth()->user();

        if (! $user) {
            $this->redirect(route('register'));

            return;
        }

        $business = $user->businesses()->first();
        $subscription = $business?->subscription;

        if (! $business || ! $subscription) {
            $this->redirect(route('onboarding'));

            return;
        }

        $currentPlan = $subscription->plan;

        if ($currentPlan && $currentPlan->id === $plan->id) {
            $this->redirect(route('dashboard'));

            return;
        }

        $isPaying = (float) $currentPlan->price > 0;
        $targetIsPaid = (float) $plan->price > 0;

        if (! $isPaying && ! $targetIsPaid) {
            $subscription->update(['plan_id' => $plan->id]);
            session()->flash('success', "Plan changed to {$plan->name}.");
            $this->redirect(route('dashboard.subscription'));

            return;
        }

        if ($isPaying && ! $targetIsPaid) {
            $subscription->update(['scheduled_plan_id' => $plan->id]);
            session()->flash('success', "Your plan will be downgraded to {$plan->name} on {$subscription->ends_at->format('M j, Y')} when your current billing period ends.");
            $this->redirect(route('dashboard.subscription'));

            return;
        }

        if (! $isPaying && $targetIsPaid) {
            $amountInKobo = (int) ((float) $plan->price * 100);
            $this->initiatePaystackPayment($plan, $subscription, $amountInKobo);

            return;
        }

        if ($isPaying && $targetIsPaid && $plan->sort_order > $currentPlan->sort_order) {
            $proratedCredit = $this->calculateProratedCredit($subscription, $currentPlan);
            $amountToCharge = max(0, (float) $plan->price - $proratedCredit);
            $amountInKobo = (int) ($amountToCharge * 100);

            if ($amountInKobo <= 0) {
                $subscription->update(['plan_id' => $plan->id]);
                $subscription->update(['ends_at' => now()->addMonth()]);
                session()->flash('success', "Upgraded to {$plan->name}.");
                $this->redirect(route('dashboard.subscription'));

                return;
            }

            $this->initiatePaystackPayment($plan, $subscription, $amountInKobo);

            return;
        }

        if ($isPaying && $targetIsPaid && $plan->sort_order < $currentPlan->sort_order) {
            $subscription->update(['scheduled_plan_id' => $plan->id]);
            session()->flash('success', "Your plan will be changed to {$plan->name} on {$subscription->ends_at->format('M j, Y')} when your current billing period ends.");
            $this->redirect(route('dashboard.subscription'));

            return;
        }

        session()->flash('error', 'Unable to process plan change.');
        $this->redirect(route('dashboard.subscription'));
    }

    private function calculateProratedCredit($subscription, Plan $currentPlan): float
    {
        $now = now();
        $endsAt = $subscription->ends_at ?: $now->copy()->addMonth();
        $totalDays = $now->diffInDays($endsAt->copy()->subMonth(), true) ?: 30;
        $remainingDays = max(0, $now->diffInDays($endsAt, true));
        $dailyRate = (float) $currentPlan->price / max(1, $totalDays);

        return round($dailyRate * $remainingDays, 2);
    }

    private function initiatePaystackPayment(Plan $plan, $subscription, int $amountInKobo): void
    {
        $user = auth()->user();

        try {
            $response = Paystack::transactions()->initialize([
                'email' => $user->email,
                'amount' => $amountInKobo,
                'callback_url' => route('subscription.paystack.callback'),
                'metadata' => [
                    'plan_id' => $plan->id,
                    'subscription_id' => $subscription->id,
                    'user_id' => $user->id,
                    'action' => 'upgrade',
                ],
            ]);

            if (! ($response['status'] ?? false) || ! ($response['data']['authorization_url'] ?? null)) {
                Log::error('Paystack upgrade initialization failed', [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'response' => $response,
                ]);
                session()->flash('error', 'Failed to initialize payment. Please try again.');

                $this->redirect(route('dashboard.subscription'));

                return;
            }

            session([
                'paystack_upgrade_authorization_url' => $response['data']['authorization_url'],
                'paystack_upgrade_reference' => $response['data']['reference'],
                'paystack_upgrade_data' => [
                    'plan_id' => $plan->id,
                    'subscription_id' => $subscription->id,
                ],
            ]);

            $this->redirect(route('subscription.paystack.redirect'));

        } catch (\Throwable $e) {
            Log::error('Paystack upgrade exception', [
                'error' => $e->getMessage(),
                'plan_id' => $plan->id,
            ]);
            session()->flash('error', 'Payment could not be initiated. Please try again.');
            $this->redirect(route('dashboard.subscription'));
        }
    }

    public function render()
    {
        $plans = Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.subscription.pricing-page', [
            'plans' => $plans,
        ]);
    }
}
