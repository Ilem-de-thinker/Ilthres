{{-- Inquiry form --}}
<div>
    @if ($submitted)
        <div class="rounded-lg border border-green-200 bg-green-50 p-6 text-center">
            <svg class="mx-auto h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="mt-3 text-lg font-semibold text-green-900">Inquiry Submitted!</h3>
            <p class="mt-1 text-sm text-green-700">The business will contact you shortly.</p>
            @if ($business)
                <a href="{{ route('business.profile', ['business' => $business->slug]) }}"
                    class="mt-4 inline-block rounded-lg bg-green-700 px-4 py-2 text-sm font-semibold text-white hover:bg-green-800">
                    Back to Business
                </a>
            @endif
        </div>
    @else
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">
                @if ($item)
                    Inquire about: {{ $item->name }}
                @else
                    Send an Inquiry
                @endif
            </h2>

            @if ($item)
                <div class="mb-4 flex items-center gap-4 rounded-lg bg-gray-50 p-4">
                    @if (is_array($item->images) && count($item->images))
                        <img src="{{ asset('storage/'.$item->images[0]) }}" alt="" class="h-16 w-16 rounded object-cover">
                    @endif
                    <div>
                        <p class="font-medium text-gray-900">{{ $item->name }}</p>
                        <p class="text-sm text-gray-500">₦{{ number_format($item->price, 2) }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 border border-red-200">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

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
                    @if ($item)
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Quantity</label>
                            <input type="number" wire:model="quantity" min="1"
                                class="mt-1 block w-24 rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none">
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Message *</label>
                        <textarea wire:model="message" rows="4"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-indigo-500 focus:outline-none"
                            placeholder="Tell the business what you need..."></textarea>
                    </div>
                </div>

                <div class="mt-6">
                    <button type="submit" wire:loading.attr="disabled" wire:processing
                        class="w-full rounded-lg bg-gray-900 px-4 py-3 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                        <span wire:loading.remove wire:processing>Send Inquiry</span>
                        <span wire:loading wire:processing>Sending...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
