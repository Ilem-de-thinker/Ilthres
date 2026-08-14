<?php

namespace App\Domains\Order\Livewire;

use App\Models\Business;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Inquiry extends Component
{
    public ?int $businessId = null;

    public ?Business $business = null;

    public ?int $productId = null;

    public ?int $serviceId = null;

    public string $buyerName = '';

    public string $buyerEmail = '';

    public string $buyerPhone = '';

    public string $message = '';

    public int $quantity = 1;

    public bool $submitted = false;

    public function mount(?int $businessId = null, ?int $productId = null, ?int $serviceId = null): void
    {
        $this->businessId = $businessId;
        $this->business = Business::find($businessId);
        $this->productId = $productId;
        $this->serviceId = $serviceId;

        $user = auth()->user();
        if ($user) {
            $this->buyerName = $user->name;
            $this->buyerEmail = $user->email;
        }
    }

    public function rules(): array
    {
        return [
            'buyerName' => ['required', 'string', 'max:255'],
            'buyerEmail' => ['required', 'email', 'max:255'],
            'buyerPhone' => ['nullable', 'string', 'max:20'],
            'message' => ['required', 'string', 'max:1000'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function submit(): void
    {
        $this->validate();

        try {
            $item = null;
            $itemName = '';
            $unitPrice = 0.0;

            if ($this->productId) {
                $item = Product::find($this->productId);
            } elseif ($this->serviceId) {
                $item = Service::find($this->serviceId);
            }

            if ($item) {
                $itemName = $item->name;
                $unitPrice = (float) $item->price;
            }

            $total = $unitPrice * $this->quantity;

            $order = Order::create([
                'business_id' => $this->businessId,
                'order_number' => Order::generateOrderNumber(),
                'type' => 'inquiry',
                'status' => 'pending',
                'buyer_name' => $this->buyerName,
                'buyer_email' => $this->buyerEmail,
                'buyer_phone' => $this->buyerPhone,
                'buyer_message' => $this->message,
                'subtotal' => $total,
                'total' => $total,
            ]);

            if ($item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $this->productId,
                    'service_id' => $this->serviceId,
                    'name' => $itemName,
                    'quantity' => $this->quantity,
                    'unit_price' => $unitPrice,
                    'total' => $total,
                ]);
            }

            $this->submitted = true;

            session()->flash('success', 'Your inquiry has been submitted! The business will contact you shortly.');
        } catch (\Throwable $e) {
            Log::error('Inquiry submission failed', [
                'business_id' => $this->businessId,
                'error' => $e->getMessage(),
            ]);
            $this->addError('form', 'Failed to submit inquiry. Please try again.');
        }
    }

    public function render()
    {
        $item = null;
        if ($this->productId) {
            $item = Product::find($this->productId);
        } elseif ($this->serviceId) {
            $item = Service::find($this->serviceId);
        }

        return view('livewire.order.inquiry', [
            'item' => $item,
        ]);
    }
}
