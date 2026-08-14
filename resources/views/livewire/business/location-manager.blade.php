<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage locations.</p>
        </div>
    @else
        {{-- Success banner --}}
        @if ($successMessage)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700 flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $successMessage }}</span>
                <button wire:click="$set('successMessage', null)" class="ml-auto">&times;</button>
            </div>
        @endif

        @if ($errorMessage)
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
        @endif

        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900">Business Locations</h3>
                <button wire:click="create" type="button" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Add location</button>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">City, State</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Primary</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($locations as $location)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">{{ $location->address }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $location->city }}, {{ $location->state }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if ($location->phone)
                                        <div>{{ $location->phone }}</div>
                                    @endif
                                    @if ($location->email)
                                        <div class="text-xs">{{ $location->email }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if ($location->is_primary)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            Primary
                                        </span>
                                    @else
                                        <button wire:click="setPrimary({{ $location->id }})" class="text-xs text-gray-500 hover:text-gray-900">Set as primary</button>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="edit({{ $location->id }})" class="mr-3 text-sm text-gray-600 hover:text-gray-900">Edit</button>
                                    <button wire:click="delete({{ $location->id }})" wire:confirm="Delete this location?" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No locations yet. Add your first branch location.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Location form --}}
        @if ($showForm)
            <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/30" wire:click.self="cancel">
                <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl" wire:click.self="">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editingLocationId ? 'Edit location' : 'Add location' }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">Fill in the details for this business location.</p>

                    <form wire:submit="save" class="mt-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Country</label>
                                <input wire:model="country" type="text" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                                @error('country') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">State</label>
                                <input wire:model="state" type="text" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                                @error('state') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600">City</label>
                            <input wire:model="city" type="text" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                            @error('city') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600">Address</label>
                            <textarea wire:model="address" rows="2" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm"></textarea>
                            @error('address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Postal code</label>
                                <input wire:model="postalCode" type="text" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Latitude</label>
                                <input wire:model="latitude" type="number" step="any" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                                @error('latitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Longitude</label>
                                <input wire:model="longitude" type="number" step="any" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                                @error('longitude') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Phone</label>
                                <input wire:model="phone" type="text" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600">Email</label>
                                <input wire:model="email" type="email" class="mt-1 block w-full rounded-lg border px-3 py-2 text-sm">
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <label class="flex items-center gap-2 text-sm">
                                <input wire:model="isPrimary" type="checkbox" class="rounded border-gray-300">
                                Primary location
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input wire:model="isHeadquarters" type="checkbox" class="rounded border-gray-300">
                                Headquarters
                            </label>
                        </div>

                        <div class="flex justify-end gap-3 pt-2">
                            <button type="button" wire:click="cancel" class="rounded-lg border px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                                {{ $editingLocationId ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endif
</div>
