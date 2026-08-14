<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Transaction;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class PaymentManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $filterGateway = '';

    public function render()
    {
        $query = Transaction::with('business');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('reference', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%")
                    ->orWhereHas('business', fn ($b) => $b->where('name', 'like', "%{$this->search}%"));
            });
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        if ($this->filterGateway) {
            $query->where('gateway', $this->filterGateway);
        }

        $transactions = $query->latest('paid_at')->paginate(20);

        $gateways = Transaction::select('gateway')->distinct()->pluck('gateway');

        return view('livewire.administration.payment-manager', [
            'transactions' => $transactions,
            'gateways' => $gateways,
        ]);
    }
}
