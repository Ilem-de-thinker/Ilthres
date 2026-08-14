<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Bookings</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your service bookings</p>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session()->has('error'))
        <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <select wire:model="statusFilter" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="cancelled">Cancelled</option>
        </select>

        <input type="date" wire:model="dateFilter" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white" placeholder="Filter by date" />

        <input type="text" wire:model="searchQuery" class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white flex-1 min-w-[200px]" placeholder="Search by name, phone, or email..." />
    </div>

    {{-- Bookings List --}}
    <div class="space-y-3">
        @forelse($this->bookings as $booking)
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $booking->customer_name }}</h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ match($booking->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                    'confirmed' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                    'in_progress' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                    'cancelled' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    default => 'bg-gray-100 text-gray-800'
                                } }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $booking->service->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-500">
                            {{ \Carbon\Carbon::parse($booking->booking_date)->format('M j, Y') }} at {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-500">{{ $booking->customer_phone }}</p>
                        @if($booking->notes)
                            <p class="text-sm text-gray-500 dark:text-gray-500 mt-1 italic">"{{ Str::limit($booking->notes, 80) }}"</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 ml-4">
                        <button wire:click="viewBooking({{ $booking->id }})" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View</button>

                        @if($booking->status === 'pending')
                            <button wire:click="updateStatus({{ $booking->id }}, 'confirmed')" class="text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">Confirm</button>
                        @endif

                        @if($booking->status === 'confirmed')
                            <button wire:click="updateStatus({{ $booking->id }}, 'in_progress')" class="text-sm text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300">Start</button>
                        @endif

                        @if($booking->status === 'in_progress')
                            <button wire:click="updateStatus({{ $booking->id }}, 'completed')" class="text-sm text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300">Complete</button>
                        @endif

                        @if(in_array($booking->status, ['pending', 'confirmed']))
                            <button wire:click="viewBooking({{ $booking->id }})" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">Cancel</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No bookings found.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $this->bookings->links() }}
    </div>

    {{-- Booking Detail Modal --}}
    @if($selectedBooking)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" wire:click="$set('selectedBooking', null)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-lg w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Booking Details</h3>

                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Customer</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBooking->customer_name }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBooking->customer_phone }}</p>
                        </div>
                        @if($selectedBooking->customer_email)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBooking->customer_email }}</p>
                        </div>
                        @endif
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Service</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBooking->service->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Date & Time</span>
                            <p class="text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($selectedBooking->booking_date)->format('l, F j, Y') }} at {{ \Carbon\Carbon::parse($selectedBooking->booking_time)->format('g:i A') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</span>
                            <p class="text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $selectedBooking->status)) }}</p>
                        </div>
                        @if($selectedBooking->notes)
                        <div>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</span>
                            <p class="text-gray-900 dark:text-white">{{ $selectedBooking->notes }}</p>
                        </div>
                        @endif
                    </div>

                    {{-- Cancel Form --}}
                    @if(in_array($selectedBooking->status, ['pending', 'confirmed']))
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cancellation Reason</label>
                            <textarea wire:model="cancelReason" rows="2" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 text-sm bg-white dark:bg-gray-800 text-gray-900 dark:text-white mb-2" placeholder="Reason for cancellation..."></textarea>
                            @error('cancelReason') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            <button wire:click="cancelBooking({{ $selectedBooking->id }})" class="px-4 py-2 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700">Cancel Booking</button>
                        </div>
                    @endif

                    <div class="mt-4 flex justify-end">
                        <button wire:click="$set('selectedBooking', null)" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
