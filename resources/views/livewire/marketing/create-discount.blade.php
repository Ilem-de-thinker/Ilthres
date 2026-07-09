<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to add discounts.</p>
        </div>
        @php return; @endphp
    @endif

    <form wire:submit="save" class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Create Discount Code</h2>
            <p class="mt-1 text-sm text-gray-500">Set up a new promotional code for your customers.</p>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Code <span class="text-red-500">*</span></label>
                    <input wire:model="code" id="code" type="text" maxlength="50" placeholder="SAVE20"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('code') border-red-300 @enderror">
                    @error('code') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select wire:model="type" id="type"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        <option value="percentage">Percentage (%)</option>
                        <option value="fixed_amount">Fixed Amount (?)</option>
                    </select>
                </div>

                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700">Value <span class="text-red-500">*</span></label>
                    <input wire:model="value" id="value" type="number" step="any"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('value') border-red-300 @enderror">
                    @error('value') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="minOrder" class="block text-sm font-medium text-gray-700">Min. Order (?)</label>
                    <input wire:model="minOrder" id="minOrder" type="number" step="any"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label for="maxUses" class="block text-sm font-medium text-gray-700">Max Uses</label>
                    <input wire:model="maxUses" id="maxUses" type="number" placeholder="Leave empty for unlimited"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                </div>

                <div>
                    <label for="expiresAt" class="block text-sm font-medium text-gray-700">Expires At</label>
                    <input wire:model="expiresAt" id="expiresAt" type="date"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                    @error('expiresAt') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('dashboard.marketing.discounts') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900">Cancel</a>
            <button type="submit" class="rounded-lg bg-gray-900 px-8 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">Create Discount</button>
        </div>
    </form>
</div>
