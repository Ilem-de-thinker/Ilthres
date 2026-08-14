<?php

namespace App\Domains\Administration\Livewire;

use App\Domains\Notifications\Notifications\PricingReEnabled;
use App\Models\PlatformSetting;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PlatformSettingsManager extends Component
{
    public bool $pricingEnabled = true;

    public ?string $lastToggledAt = null;

    public function mount(): void
    {
        $this->pricingEnabled = (bool) PlatformSetting::get('pricing_enabled', true);

        $setting = PlatformSetting::where('key', 'pricing_enabled')->first();
        $this->lastToggledAt = $setting?->updated_at?->format('M j, Y g:ia') ?? 'Never';
    }

    public function togglePricing(): void
    {
        $wasEnabled = (bool) PlatformSetting::get('pricing_enabled', true);

        PlatformSetting::set('pricing_enabled', ! $this->pricingEnabled);

        $this->pricingEnabled = ! $this->pricingEnabled;

        $setting = PlatformSetting::where('key', 'pricing_enabled')->first();
        $this->lastToggledAt = $setting?->updated_at?->format('M j, Y g:ia') ?? 'Never';

        // If pricing was just re-enabled (OFF → ON), notify all business owners.
        if ($wasEnabled === false && $this->pricingEnabled === true) {
            $this->notifyBusinessOwners();
        }

        $status = $this->pricingEnabled ? 'enabled' : 'disabled';
        session()->flash('success', "Pricing has been {$status}.");
    }

    private function notifyBusinessOwners(): void
    {
        $users = User::whereHas('businesses', function ($q) {
            $q->where('is_active', true);
        })->get();

        foreach ($users as $user) {
            $user->notify(new PricingReEnabled);
        }
    }

    public function render()
    {
        return view('livewire.administration.platform-settings-manager');
    }
}
