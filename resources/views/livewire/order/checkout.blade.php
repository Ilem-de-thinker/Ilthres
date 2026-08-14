{{-- Checkout page --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
        @if ($business)
            <p class="mt-1 text-sm text-gray-500">Ordering from {{ $business->name }}</p>
        @endif
    </div>

    @if ($errors->any())
        <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        {{-- Order summary --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Order Summary</h2>
            <div class="divide-y divide-gray-100">
                @foreach ($items as $item)
                    <div class="flex items-center justify-between py-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }} × ₦{{ number_format($item['price'], 2) }}</p>
                        </div>
                        <p class="text-sm font-medium text-gray-900">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 border-t pt-4">
                <div class="flex items-center justify-between">
                    <p class="text-lg font-bold text-gray-900">Total</p>
                    <p class="text-lg font-bold text-gray-900">₦{{ number_format($this->subtotal, 2) }}</p>
                </div>
            </div>
        </div>

        {{-- Buyer info form --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Your Information</h2>
            <form wire:submit="submit">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                        <input type="text" wire:model="buyerName"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" wire:model="buyerEmail"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" wire:model="buyerPhone"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message / Special Instructions</label>
                        <textarea wire:model="buyerMessage" rows="3"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                            placeholder="Any special requests or delivery instructions..."></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" wire:loading.attr="disabled" wire:processing
                        class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                        <span wire:loading.remove wire:processing>Pay ₦{{ number_format($this->subtotal, 2) }} & Place Order</span>
                        <span wire:loading wire:processing>Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
