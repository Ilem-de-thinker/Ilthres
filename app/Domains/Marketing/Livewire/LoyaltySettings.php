<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateLoyaltyProgram;
use App\Domains\Marketing\Actions\UpdateLoyaltyProgram;
use App\Models\Business;
use App\Models\LoyaltyProgram;
use App\Models\LoyaltyPointsTransaction;
use Livewire\Component;

class LoyaltySettings extends Component
{
    public ?Business $business = null;
    public ?LoyaltyProgram $program = null;
    public string $name = 'Loyalty Program';
    public bool $isActive = true;
    public array $earningRules = [];
    public array $rewards = [];

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'isActive' => ['boolean'],
        ];
    }

    public function mount(): void
    {
        $user = auth()->user();
        $businesses = $user->businesses;
        if ($businesses->isEmpty()) { return; }
        $defaultId = session('current_business_id', $businesses->first()->id);
        $this->business = $businesses->find($defaultId) ?? $businesses->first();

        if ($this->business) {
            $this->program = LoyaltyProgram::where('business_id', $this->business->id)->first();
            if ($this->program) {
                $this->name = $this->program->name;
                $this->isActive = $this->program->is_active;
                $this->earningRules = $this->program->earning_rules ?? [
                    ['action' => 'visit', 'points' => 1, 'label' => 'Visit business page'],
                    ['action' => 'review', 'points' => 10, 'label' => 'Leave a review'],
                    ['action' => 'referral', 'points' => 50, 'label' => 'Refer a friend'],
                ];
                $this->rewards = $this->program->rewards ?? [
                    ['points' => 100, 'label' => '5% discount code'],
                    ['points' => 200, 'label' => '10% discount code'],
                ];
            } else {
                $this->earningRules = [
                    ['action' => 'visit', 'points' => 1, 'label' => 'Visit business page'],
                    ['action' => 'review', 'points' => 10, 'label' => 'Leave a review'],
                    ['action' => 'referral', 'points' => 50, 'label' => 'Refer a friend'],
                ];
                $this->rewards = [
                    ['points' => 100, 'label' => '5% discount code'],
                    ['points' => 200, 'label' => '10% discount code'],
                ];
            }
        }
    }

    public function addRule(): void
    {
        $this->earningRules[] = ['action' => '', 'points' => 0, 'label' => ''];
    }

    public function removeRule(int $index): void
    {
        unset($this->earningRules[$index]);
        $this->earningRules = array_values($this->earningRules);
    }

    public function addReward(): void
    {
        $this->rewards[] = ['points' => 0, 'label' => ''];
    }

    public function removeReward(int $index): void
    {
        unset($this->rewards[$index]);
        $this->rewards = array_values($this->rewards);
    }

    public function save(CreateLoyaltyProgram $createAction, UpdateLoyaltyProgram $updateAction): void
    {
        $this->validate();
        if (! $this->business) { return; }

        $data = [
            'business_id' => $this->business->id,
            'name' => $this->name,
            'earning_rules' => $this->earningRules,
            'rewards' => $this->rewards,
            'is_active' => $this->isActive,
        ];

        if ($this->program) {
            $updateAction($this->program, $data);
        } else {
            $this->program = $createAction($data);
        }

        $this->dispatch('loyalty-saved');
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.loyalty-settings', ['hasBusiness' => false]);
        }

        $recentTransactions = LoyaltyPointsTransaction::where('business_id', $this->business->id)
            ->latest()->limit(10)->get();

        return view('livewire.marketing.loyalty-settings', [
            'hasBusiness' => true,
            'recentTransactions' => $recentTransactions,
        ]);
    }
}
