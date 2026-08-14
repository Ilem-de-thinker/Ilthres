{{-- Shopping cart page --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Shopping Cart</h1>
        @if ($business)
            <p class="mt-1 text-sm text-gray-500">Items from {{ $business->name }}</p>
        @endif
    </div>

    @if (empty($items))
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Your cart is empty.</p>
            @if ($business)
                <a href="{{ route('business.profile', ['business' => $business->slug]) }}"
                    class="mt-4 inline-block rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Continue Shopping
                </a>
            @endif
        </div>
    @else
        <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Quantity</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($items as $cartItemId => $item)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($item['image'])
                                        <img src="{{ asset('storage/'.$item['image']) }}" alt="" class="h-12 w-12 rounded object-cover">
                                    @else
                                        <div class="flex h-12 w-12 items-center justify-center rounded bg-gray-100 text-gray-400 text-xs">No img</div>
                                    @endif
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                                        <p class="text-xs text-gray-400">{{ $item['type'] === 'product' ? 'Product' : 'Service' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-gray-600">₦{{ number_format($item['price'], 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="updateQuantity('{{ $cartItemId }}', {{ $item['quantity'] - 1 }})"
                                        class="rounded border border-gray-300 px-2 py-0.5 text-xs hover:bg-gray-50">-</button>
                                    <span class="w-8 text-center text-sm">{{ $item['quantity'] }}</span>
                                    <button wire:click="updateQuantity('{{ $cartItemId }}', {{ $item['quantity'] + 1 }})"
                                        class="rounded border border-gray-300 px-2 py-0.5 text-xs hover:bg-gray-50">+</button>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td class="px-4 py-3 text-center">
                                <button wire:click="removeItem('{{ $cartItemId }}')"
                                    class="text-xs text-red-600 hover:text-red-500">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex items-center justify-between rounded-lg border border-gray-200 bg-white p-4">
            <div>
                <p class="text-sm text-gray-500">Subtotal</p>
                <p class="text-xl font-bold text-gray-900">₦{{ number_format($this->subtotal, 2) }}</p>
            </div>
            <div class="flex gap-3">
                <button wire:click="clearCart"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Clear Cart
                </button>
                @if ($business)
                    @if ($business->order_mode === 'checkout')
                        <a href="{{ route('order.checkout', ['business' => $business->slug]) }}"
                            class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                            Proceed to Checkout
                        </a>
                    @else
                        <a href="{{ route('order.inquiry', ['business' => $business->slug]) }}"
                            class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                            Submit Inquiry
                        </a>
                    @endif
                @endif
            </div>
        </div>
    @endif
</div>
