<div class="max-w-2xl mx-auto">
    @if($step === 4)
        {{-- Success State --}}
        <div class="text-center py-12">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Booking Confirmed!</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">
                Your appointment has been submitted. The business will confirm shortly.
            </p>
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6 text-left">
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Service:</strong> {{ $service->name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Date:</strong> {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Time:</strong> {{ \App\Models\BookingSlot::find($selectedSlot)?->start_time }}</p>
            </div>
        </div>
    @else
        {{-- Progress Steps --}}
        <div class="flex items-center justify-center mb-8">
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }} flex items-center justify-center text-sm font-medium">1</div>
                <span class="ml-2 text-sm {{ $step >= 1 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">Date</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-200 dark:bg-gray-700 mx-4"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }} flex items-center justify-center text-sm font-medium">2</div>
                <span class="ml-2 text-sm {{ $step >= 2 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">Time</span>
            </div>
            <div class="w-12 h-0.5 bg-gray-200 dark:bg-gray-700 mx-4"></div>
            <div class="flex items-center">
                <div class="w-8 h-8 rounded-full {{ $step >= 3 ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-500' }} flex items-center justify-center text-sm font-medium">3</div>
                <span class="ml-2 text-sm {{ $step >= 3 ? 'text-gray-900 dark:text-white' : 'text-gray-500' }}">Details</span>
            </div>
        </div>

        {{-- Step 1: Select Date --}}
        @if($step === 1)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Select a Date</h3>
                <input
                    type="date"
                    wire:model="selectedDate"
                    min="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                @error('selectedDate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        @endif

        {{-- Step 2: Select Time --}}
        @if($step === 2)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Select a Time</h3>
                @if(empty($availableSlots))
                    <p class="text-gray-500 dark:text-gray-400">No available slots for this date. Please try another date.</p>
                @else
                    <div class="grid grid-cols-3 gap-3">
                        @foreach($availableSlots as $slot)
                            <button
                                wire:click="selectSlot({{ $slot['id'] }})"
                                class="px-4 py-3 border rounded-lg text-center transition-colors {{ $selectedSlot == $slot['id'] ? 'border-blue-600 bg-blue-50 dark:bg-blue-900/30 text-blue-600' : 'border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:border-blue-400' }}"
                            >
                                {{ \Carbon\Carbon::parse($slot['start_time'])->format('g:i A') }}
                                <span class="block text-xs text-gray-500">{{ $slot['remaining_slots'] ?? ($slot['max_bookings'] - $slot['current_bookings']) }} spots left</span>
                            </button>
                        @endforeach
                    </div>
                @endif
                <button wire:click="goToStep(1)" class="mt-4 text-sm text-blue-600 hover:underline">&larr; Change date</button>
            </div>
        @endif

        {{-- Step 3: Fill Info --}}
        @if($step === 3)
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Your Details</h3>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full Name *</label>
                        <input
                            type="text"
                            wire:model="customerName"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Your full name"
                        />
                        @error('customerName')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number *</label>
                        <input
                            type="tel"
                            wire:model="customerPhone"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="08012345678"
                        />
                        @error('customerPhone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email (optional)</label>
                        <input
                            type="email"
                            wire:model="customerEmail"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="you@email.com"
                        />
                        @error('customerEmail')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes (optional)</label>
                        <textarea
                            wire:model="notes"
                            rows="3"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-gray-900 dark:text-white bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Any special requests or notes..."
                        ></textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Summary --}}
                <div class="mt-6 bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="font-medium text-gray-900 dark:text-white mb-2">Booking Summary</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Service: {{ $service->name }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Date: {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</p>
                    @if($service->price > 0)
                        <p class="text-sm text-gray-600 dark:text-gray-400">Price: {{ $service->currency ?? 'NGN' }} {{ number_format($service->price, 2) }}</p>
                    @endif
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="goToStep(2)" class="px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800">
                        &larr; Back
                    </button>
                    <button
                        wire:click="submit"
                        wire:loading.attr="disabled"
                        class="flex-1 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 font-medium"
                    >
                        <span wire:loading.remove wire:target="submit">Confirm Booking</span>
                        <span wire:loading wire:target="submit">Processing...</span>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
