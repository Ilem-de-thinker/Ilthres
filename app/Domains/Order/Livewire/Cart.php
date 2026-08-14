<?php

namespace App\Domains\Order\Livewire;

use App\Models\Business;
use App\Models\Product;
use App\Models\Service;
use Livewire\Component;

class Cart extends Component
{
    public ?int $businessId = null;

    public ?Business $business = null;

    public array $items = [];

    public function mount(?int $businessId = null): void
    {
        $this->businessId = $businessId;
        $this->loadBusiness();
        $this->loadCart();
    }

    public function loadBusiness(): void
    {
        if ($this->businessId) {
            $this->business = Business::find($this->businessId);
        }
    }

    public function loadCart(): void
    {
        $this->items = session('cart.'.$this->businessId, []);
    }

    public function addItem(string $type, int $id, int $quantity = 1): void
    {
        $item = $type === 'product'
            ? Product::find($id)
            : Service::find($id);

        if (! $item || ! $item->is_active) {
            session()->flash('error', 'Item not available.');

            return;
        }

        $cartKey = 'cart.'.$this->businessId;
        $cart = session($cartKey, []);

        $cartItemId = $type.'_'.$id;

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $quantity;
        } else {
            $cart[$cartItemId] = [
                'type' => $type,
                'id' => $id,
                'name' => $item->name,
                'price' => (float) $item->price,
                'quantity' => $quantity,
                'image' => is_array($item->images) ? ($item->images[0] ?? null) : null,
            ];
        }

        session([$cartKey => $cart]);
        $this->items = $cart;

        session()->flash('success', "{$item->name} added to cart.");
    }

    public function removeItem(string $cartItemId): void
    {
        $cartKey = 'cart.'.$this->businessId;
        $cart = session($cartKey, []);

        unset($cart[$cartItemId]);

        session([$cartKey => $cart]);
        $this->items = $cart;
    }

    public function updateQuantity(string $cartItemId, int $quantity): void
    {
        if ($quantity < 1) {
            $this->removeItem($cartItemId);

            return;
        }

        $cartKey = 'cart.'.$this->businessId;
        $cart = session($cartKey, []);

        if (isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] = $quantity;
        }

        session([$cartKey => $cart]);
        $this->items = $cart;
    }

    public function clearCart(): void
    {
        session()->forget('cart.'.$this->businessId);
        $this->items = [];
    }

    public function getSubtotalProperty(): float
    {
        return array_reduce($this->items, fn ($carry, $item) => $carry + ($item['price'] * $item['quantity']), 0.0);
    }

    public function render()
    {
        return view('livewire.order.cart');
    }
}
