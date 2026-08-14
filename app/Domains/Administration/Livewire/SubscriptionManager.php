<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Subscription;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class SubscriptionManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public function render()
    {
        $query = Subscription::with(['business', 'plan']);

        if ($this->search) {
            $query->whereHas('business', fn ($b) => $b->where('name', 'like', "%{$this->search}%")
            );
        }

        if ($this->filterStatus) {
            $query->where('status', $this->filterStatus);
        }

        $subscriptions = $query->latest('created_at')->paginate(20);

        return view('livewire.administration.subscription-manager', [
            'subscriptions' => $subscriptions,
        ]);
    }
}
