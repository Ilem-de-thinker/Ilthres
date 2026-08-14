<?php

namespace App\Domains\Order\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class SellerOrders extends Component
{
    use WithPagination;

    public string $search = '';

    public string $filterStatus = '';

    public ?int $viewingOrderId = null;

    public function viewingOrder(int $orderId): void
    {
        $businessId = session('current_business_id') ?: auth()->user()->businesses()->first()?->id;
        $order = Order::with('items')->where('business_id', $businessId)->find($orderId);

        if ($order) {
            $this->viewingOrderId = $orderId;
        }
    }

    public function closeOrder(): void
    {
        $this->viewingOrderId = null;
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $businessId = session('current_business_id') ?: auth()->user()->businesses()->first()?->id;
        $order = Order::where('business_id', $businessId)->findOrFail($orderId);

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

    public function render()
    {
        $businessId = session('current_business_id') ?: auth()->user()->businesses()->first()?->id;

        $orders = Order::with('items')
            ->where('business_id', $businessId)
            ->when($this->search, fn ($q) => $q->where(function ($q) {
                $q->where('order_number', 'like', "%{$this->search}%")
                    ->orWhere('buyer_name', 'like', "%{$this->search}%")
                    ->orWhere('buyer_email', 'like', "%{$this->search}%");
            }))
            ->when($this->filterStatus, fn ($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(20);

        $viewingOrder = null;
        if ($this->viewingOrderId) {
            $viewingOrder = Order::with('items.product', 'items.service')
                ->where('business_id', $businessId)
                ->find($this->viewingOrderId);
        }

        return view('livewire.order.seller-orders', [
            'orders' => $orders,
            'viewingOrder' => $viewingOrder,
        ]);
    }
}
