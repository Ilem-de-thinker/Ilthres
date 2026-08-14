<?php

namespace App\Domains\Marketing\Livewire;

use App\Domains\Marketing\Actions\CreateDiscount as CreateDiscountAction;
use App\Models\Business;
use Livewire\Component;

class CreateDiscount extends Component
{
    public ?Business $business = null;

    public string $code = '';

    public string $type = 'percentage';

    public float $value = 0;

    public float $minOrder = 0;

    public ?int $maxUses = null;

    public ?string $expiresAt = null;

    public string $scope = 'all';

    protected function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:50'],
            'type' => ['required', 'in:percentage,fixed_amount'],
            'value' => ['required', 'numeric', 'min:0.01'],
            'minOrder' => ['nullable', 'numeric', 'min:0'],
            'maxUses' => ['nullable', 'integer', 'min:1'],
            'expiresAt' => ['nullable', 'date', 'after:today'],
            'scope' => ['required', 'in:all,specific'],
        ];
    }

    protected $messages = [
        'code.required' => 'Enter a discount code.',
        'value.required' => 'Enter a discount value.',
        'value.min' => 'Value must be greater than 0.',
    ];

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

    public function save(CreateDiscountAction $action): void
    {
        $this->validate();
        if (! $this->business) {
            return;
        }

        $action([
            'business_id' => $this->business->id,
            'code' => strtoupper($this->code),
            'type' => $this->type,
            'value' => $this->value,
            'min_order' => $this->minOrder,
            'max_uses' => $this->maxUses,
            'expires_at' => $this->expiresAt,
            'scope' => $this->scope,
            'is_active' => true,
        ]);

        $this->redirect(route('dashboard.marketing.discounts'), navigate: true);
    }

    public function render()
    {
        if (! $this->business) {
            return view('livewire.marketing.create-discount', ['hasBusiness' => false]);
        }

        return view('livewire.marketing.create-discount', ['hasBusiness' => true]);
    }
}
