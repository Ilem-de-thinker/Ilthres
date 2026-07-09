<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage banners.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">Promo Banners</h2>
                <p class="mt-1 text-sm text-gray-500">Manage announcement banners on your public page.</p>

                <div class="mt-4 space-y-3">
                    @forelse ($banners as $banner)
                        <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $banner->message }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">{{ ucfirst($banner->position) }} - {{ $banner->starts_at?->format('d M') ?? 'Now' }} to {{ $banner->ends_at?->format('d M Y') ?? 'Forever' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                @if ($banner->is_active)
                                    <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">Active</span>
                                @else
                                    <span class="inline-flex rounded-full bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-500">Inactive</span>
                                @endif
                                <button wire:click="edit({{ $banner->id }})" class="text-xs font-medium text-gray-600 hover:text-gray-900">Edit</button>
                            </div>
                        </div>
                    @empty
                        <p class="py-4 text-center text-sm text-gray-400">No banners yet. Create one below.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div>
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-gray-900">{{ $editId ? 'Edit' : 'Create' }} Banner</h2>

                <form wire:submit="save" class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Message</label>
                        <input wire:model="message" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900 @error('message') border-red-300 @enderror">
                        @error('message') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Position</label>
                        <select wire:model="position" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="top">Top</option>
                            <option value="bottom">Bottom</option>
                            <option value="hero">Hero</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">Style</label>
                        <select wire:model="style" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            <option value="announcement">Announcement</option>
                            <option value="sale">Sale</option>
                            <option value="seasonal">Seasonal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">CTA Text</label>
                        <input wire:model="ctaText" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700">CTA URL</label>
                        <input wire:model="ctaUrl" type="url" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        @error('ctaUrl') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Start</label>
                            <input wire:model="startsAt" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">End</label>
                            <input wire:model="endsAt" type="date" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                            @error('endsAt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">{{ $editId ? 'Update' : 'Create' }} Banner</button>
                </form>
            </div>
        </div>
    </div>
</div>
