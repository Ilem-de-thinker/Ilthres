<div>
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Availability</h2>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Set available hours for <strong>{{ $service->name }}</strong></p>
    </div>

    @if(session()->has('success'))
        <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    {{-- Generate Slots Button --}}
    <div class="mb-6">
        <button wire:click="generateSlots" wire:loading.attr="disabled" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 text-sm font-medium">
            <span wire:loading.remove wire:target="generateSlots">Generate Slots (Next 30 Days)</span>
            <span wire:loading wire:target="generateSlots">Generating...</span>
        </button>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Generate bookable time slots based on your availability settings below.</p>
    </div>

    {{-- Weekly Schedule --}}
    <div class="space-y-3">
        @foreach(['mon' => 'Monday', 'tue' => 'Tuesday', 'wed' => 'Wednesday', 'thu' => 'Thursday', 'fri' => 'Friday', 'sat' => 'Saturday', 'sun' => 'Sunday'] as $day => $dayName)
            @php $slot = $availability[$day] ?? null; @endphp
            <div class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg {{ !$slot || !$slot['is_active'] ? 'opacity-60' : '' }}">
                <div class="w-24">
                    <span class="font-medium text-gray-900 dark:text-white">{{ $dayName }}</span>
                </div>

                @if($slot && $slot['is_active'])
                    <div class="flex-1 flex items-center gap-4">
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($slot['start_time'])->format('g:i A') }} - {{ \Carbon\Carbon::parse($slot['end_time'])->format('g:i A') }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-500">
                            {{ $slot['slot_duration_minutes'] }}min slots, max {{ $slot['max_bookings_per_slot'] }} bookings/slot
                        </span>
                    </div>
                    <button wire:click="toggleDay('{{ $day }}')" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400">Disable</button>
                @else
                    <div class="flex-1">
                        <span class="text-sm text-gray-500 dark:text-gray-400">Unavailable</span>
                    </div>
                    @if(!$slot)
                        <button wire:click="editDay('{{ $day }}')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Set Hours</button>
                    @else
                        <button wire:click="toggleDay('{{ $day }}')" class="text-sm text-green-600 hover:text-green-800 dark:text-green-400">Enable</button>
                        <button wire:click="editDay('{{ $day }}')" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400">Edit</button>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    {{-- Edit Modal --}}
    @if($editingDay)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-gray-500/75 dark:bg-gray-900/75" wire:click="$set('editingDay', null)"></div>
                <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Set Hours - {{ ucfirst($editingDay) }}
                    </h3>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Time</label>
                                <input type="time" wire:model="start_time" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                                @error('start_time') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Time</label>
                                <input type="time" wire:model="end_time" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                                @error('end_time') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Slot Duration (minutes)</label>
                            <select wire:model="slot_duration_minutes" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
                                <option value="15">15 minutes</option>
                                <option value="30">30 minutes</option>
                                <option value="45">45 minutes</option>
                                <option value="60">1 hour</option>
                                <option value="90">1.5 hours</option>
                                <option value="120">2 hours</option>
                            </select>
                            @error('slot_duration_minutes') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Bookings Per Slot</label>
                            <input type="number" wire:model="max_bookings_per_slot" min="1" max="100" class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-800 text-gray-900 dark:text-white" />
                            @error('max_bookings_per_slot') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button wire:click="$set('editingDay', null)" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 text-sm">Cancel</button>
                        <button wire:click="saveAvailability" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
