<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateReferralCode;
use App\Models\Business;
use App\Models\Referral;
use Livewire\Component;

class ReferralProgram extends Component
{
    public ?Business $business = null;
    public string $newCode = '';

    protected function rules(): array
    {
        return [
            'newCode' => ['required', 'string', 'max:50', 'alpha_dash'],
        ];
    }

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();
    }

    public function generateCode(CreateReferralCode $action): void
    {
        $this->validate();
        if (! $this->business) { return; }
        $action(['business_id' => $this->business->id, 'code' => $this->newCode]);
        $this->newCode = '';
        $this->dispatch('referral-code-created');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.referral-program', ['hasBusiness' => false]);
        }

        $referrals = Referral::where('business_id', $this->business->id)
            ->with('referrer', 'referred', 'rewards')
            ->latest()
            ->paginate(15);

        $totalReferrals = Referral::where('business_id', $this->business->id)->count();
        $convertedReferrals = Referral::where('business_id', $this->business->id)->where('status', 'converted')->count();

        return view('livewire.marketing.referral-program', [
            'hasBusiness' => true,
            'referrals' => $referrals,
            'totalReferrals' => $totalReferrals,
            'convertedReferrals' => $convertedReferrals,
        ]);
    }
}
