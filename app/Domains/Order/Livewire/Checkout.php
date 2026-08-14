<?php

namespace App\Domains\Order\Livewire;

use App\Models\Business;
use App\Models\Order;
use App\Models\OrderItem;
use HopekellDev\Paystack\Paystack;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Checkout extends Component
{
    public ?int $businessId = null;

    public ?Business $business = null;

    public array $items = [];

    public string $buyerName = '';

    public string $buyerEmail = '';

    public string $buyerPhone = '';

    public string $buyerMessage = '';

    public bool $processing = false;

    public function mount(?int $businessId = null): void
    {
        $this->businessId = $businessId;
        $this->business = Business::find($businessId);
        $this->items = session('cart.'.$businessId, []);

        if (empty($this->items)) {
            session()->flash('error', 'Your cart is empty.');
            $this->redirect(route('business.profile', ['business' => $this->business?->slug]));
        }

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
            'buyerMessage' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function getSubtotalProperty(): float
    {
        return array_reduce($this->items, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0.0);
    }

    public function submit(): void
    {
        $this->validate();
        $this->processing = true;

        try {
            if ($this->business->order_mode !== 'checkout') {
                $this->addError('form', 'This business does not accept online payments.');
                $this->processing = false;

                return;
            }

            if (! $this->business->is_verified) {
                $this->addError('form', 'This business is not yet verified and cannot accept online payments.');
                $this->processing = false;

                return;
            }

            $order = $this->createOrder();

            if ($this->subtotal > 0) {
                $this->initiatePayment($order);
            } else {
                session()->forget('cart.'.$this->businessId);
                session()->flash('success', 'Your order has been submitted! The business will contact you shortly.');
                $this->redirect(route('business.profile', ['business' => $this->business->slug]));
            }
        } catch (\Throwable $e) {
            Log::error('Order submission failed', [
                'business_id' => $this->businessId,
                'error' => $e->getMessage(),
            ]);
            $this->addError('form', 'Failed to submit order. Please try again.');
            $this->processing = false;
        }
    }

    private function createOrder(): Order
    {
        $order = Order::create([
            'business_id' => $this->businessId,
            'order_number' => Order::generateOrderNumber(),
            'type' => $this->business->order_mode === 'checkout' ? 'checkout' : 'inquiry',
            'status' => 'pending',
            'buyer_name' => $this->buyerName,
            'buyer_email' => $this->buyerEmail,
            'buyer_phone' => $this->buyerPhone,
            'buyer_message' => $this->buyerMessage,
            'subtotal' => $this->subtotal,
            'total' => $this->subtotal,
        ]);

        foreach ($this->items as $cartItem) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cartItem['type'] === 'product' ? $cartItem['id'] : null,
                'service_id' => $cartItem['type'] === 'service' ? $cartItem['id'] : null,
                'name' => $cartItem['name'],
                'quantity' => $cartItem['quantity'],
                'unit_price' => $cartItem['price'],
                'total' => $cartItem['price'] * $cartItem['quantity'],
            ]);
        }

        return $order;
    }

    private function initiatePayment(Order $order): void
    {
        if (! $this->business->is_verified) {
            throw new \RuntimeException('Only verified businesses can accept online payments.');
        }

        $amountInKobo = (int) ($this->subtotal * 100);

        $response = Paystack::transactions()->initialize([
            'email' => $this->buyerEmail,
            'amount' => $amountInKobo,
            'callback_url' => route('order.paystack.callback'),
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'business_id' => $this->businessId,
            ],
        ]);

        if (! ($response['status'] ?? false) || ! ($response['data']['authorization_url'] ?? null)) {
            Log::error('Paystack order payment initialization failed', [
                'order_id' => $order->id,
                'response' => $response,
            ]);

            throw new \RuntimeException('Payment initialization failed.');
        }

        $order->update([
            'gateway' => 'paystack',
            'gateway_reference' => $response['data']['reference'],
            'gateway_data' => $response,
        ]);

        $this->redirect($response['data']['authorization_url']);
    }

    public function render()
    {
        return view('livewire.order.checkout');
    }
}
