{{-- Seller orders management page --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
        <p class="mt-1 text-sm text-gray-500">Manage customer orders and inquiries for your business.</p>
    </div>

    <div class="mb-4 flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search orders..."
            class="flex-1 min-w-[200px] rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none">
        <select wire:model.live="filterStatus"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buyer</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Items</th>
                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($orders as $order)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">
                            {{ $order->buyer_name }}
                            @if ($order->buyer_phone)
                                <span class="block text-xs text-gray-400">{{ $order->buyer_phone }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($order->type === 'checkout')
                                <span class="inline-flex rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-700">Checkout</span>
                            @else
                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-0.5 text-xs font-medium text-amber-700">Inquiry</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $order->items->count() }}</td>
                        <td class="px-4 py-3 text-right text-sm font-medium text-gray-900">₦{{ number_format($order->total, 2) }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'confirmed' => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-indigo-100 text-indigo-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="inline-flex rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700' }} px-2.5 py-0.5 text-xs font-medium">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button wire:click="viewingOrder({{ $order->id }})"
                                    class="rounded px-2 py-1 text-xs font-medium text-indigo-600 hover:text-indigo-500">
                                    View
                                </button>
                                @if ($order->status === 'pending')
                                    <button wire:click="updateStatus({{ $order->id }}, 'confirmed')"
                                        class="rounded bg-green-50 px-2 py-1 text-xs font-medium text-green-700 hover:bg-green-100 border border-green-200">
                                        Confirm
                                    </button>
                                @endif
                                @if (in_array($order->status, ['pending', 'confirmed']))
                                    <button wire:click="updateStatus({{ $order->id }}, 'cancelled')"
                                        wire:confirm="Cancel this order?"
                                        class="rounded bg-red-50 px-2 py-1 text-xs font-medium text-red-700 hover:bg-red-100 border border-red-200">
                                        Cancel
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-sm text-gray-500">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>

    {{-- Order detail modal --}}
    @if ($viewingOrder)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" wire:click="closeOrder">
            <div class="mx-4 w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl" wire:click.stop>
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-bold text-gray-900">Order {{ $viewingOrder->order_number }}</h2>
                    <button wire:click="closeOrder" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Buyer</p>
                        <p class="font-medium">{{ $viewingOrder->buyer_name }}</p>
                        @if ($viewingOrder->buyer_email)
                            <p class="text-xs text-gray-400">{{ $viewingOrder->buyer_email }}</p>
                        @endif
                        @if ($viewingOrder->buyer_phone)
                            <p class="text-xs text-gray-400">{{ $viewingOrder->buyer_phone }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-500">Status</p>
                        <p class="font-medium">{{ ucfirst($viewingOrder->status) }}</p>
                        <p class="text-xs text-gray-400">{{ $viewingOrder->created_at->format('M j, Y g:ia') }}</p>
                    </div>
                </div>

                @if ($viewingOrder->buyer_message)
                    <div class="mt-4 rounded-lg bg-gray-50 p-3">
                        <p class="text-xs text-gray-500">Message from buyer</p>
                        <p class="mt-1 text-sm">{{ $viewingOrder->buyer_message }}</p>
                    </div>
                @endif

                <div class="mt-4">
                    <p class="text-sm font-medium text-gray-500">Items</p>
                    <table class="mt-2 min-w-full text-sm">
                        <thead>
                            <tr class="border-b text-left text-xs text-gray-500">
                                <th class="py-2">Item</th>
                                <th class="py-2 text-center">Qty</th>
                                <th class="py-2 text-right">Price</th>
                                <th class="py-2 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viewingOrder->items as $item)
                                <tr class="border-b">
                                    <td class="py-2">
                                        {{ $item->name }}
                                        @if ($item->product)
                                            <span class="text-xs text-gray-400">(Product)</span>
                                        @elseif ($item->service)
                                            <span class="text-xs text-gray-400">(Service)</span>
                                        @endif
                                    </td>
                                    <td class="py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="py-2 text-right">₦{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="py-2 text-right">₦{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-medium">
                                <td colspan="3" class="py-2 text-right">Total</td>
                                <td class="py-2 text-right">₦{{ number_format($viewingOrder->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if (in_array($viewingOrder->status, ['pending', 'confirmed', 'processing']))
                    <div class="mt-4 flex gap-2 border-t pt-4">
                        @if ($viewingOrder->status === 'pending')
                            <button wire:click="updateStatus({{ $viewingOrder->id }}, 'confirmed'); closeOrder()"
                                class="rounded bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                Confirm Order
                            </button>
                        @endif
                        @if (in_array($viewingOrder->status, ['confirmed', 'processing']))
                            <button wire:click="updateStatus({{ $viewingOrder->id }}, 'completed'); closeOrder()"
                                class="rounded bg-green-600 px-4 py-2 text-sm font-medium text-white hover:bg-green-700">
                                Mark Completed
                            </button>
                        @endif
                        <button wire:click="updateStatus({{ $viewingOrder->id }}, 'cancelled'); closeOrder()"
                            class="rounded border border-red-200 bg-red-50 px-4 py-2 text-sm font-medium text-red-700 hover:bg-red-100">
                            Cancel Order
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
