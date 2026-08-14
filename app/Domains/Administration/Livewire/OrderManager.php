<?php

namespace App\Domains\Administration\Livewire;

use App\Models\Order;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class OrderManager extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public string $filterType = '';

    public ?int $viewingOrder = null;

    public string $adminNotes = '';

    public function viewingOrder(int $orderId): void
    {
        $this->viewingOrder = $orderId;
        $order = Order::with('items')->find($orderId);
        $this->adminNotes = $order?->admin_notes ?? '';
    }

    public function closeOrder(): void
    {
        $this->viewingOrder = null;
        $this->adminNotes = '';
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $order = Order::findOrFail($orderId);
        $updates = ['status' => $status];

        if ($status === 'confirmed') {
            $updates['confirmed_at'] = now();
        } elseif ($status === 'completed') {
            $updates['completed_at'] = now();
        } elseif ($status === 'cancelled') {
            $updates['cancelled_at'] = now();
        }

        $order->update($updates);

        session()->flash('success', "Order {$order->order_number} marked as {$status}.");
    }

    public function saveAdminNotes(int $orderId): void
    {
        $order = Order::findOrFail($orderId);
        $order->update(['admin_notes' => $this->adminNotes]);

        session()->flash('success', 'Admin notes saved.');
    }

    public function render()
    {
        $orders = Order::with(['business', 'items'])
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('order_number', 'like', "%{$this->search}%")
                    ->orWhere('buyer_name', 'like', "%{$this->search}%")
                    ->orWhere('buyer_email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterType, fn ($q) => $q->where('type', $this->filterType))
            ->latest()
            ->paginate(20);

        return view('livewire.administration.order-manager', [
            'orders' => $orders,
            'viewingOrder' => $this->viewingOrder ? Order::with(['business', 'items.product', 'items.service'])->find($this->viewingOrder) : null,
        ]);
    }
}
