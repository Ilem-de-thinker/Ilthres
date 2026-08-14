<?php

namespace App\Domains\Payment\Livewire;

use App\Models\Business;
use App\Models\Transaction;
use Livewire\Component;

class BillingHistory extends Component
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
            return view('livewire.payment.billing-history', [
                'hasBusiness' => false,
            ]);
        }

        $transactions = Transaction::where('business_id', $this->business->id)
            ->latest()
            ->paginate(10);

        return view('livewire.payment.billing-history', [
            'hasBusiness' => true,
            'transactions' => $transactions,
        ]);
    }
}
