{{--
  Create marketing link form.
--}}
<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to create marketing links.</p>
        </div>
    @else
        <form wire:submit="save" class="space-y-6">
            <div class="rounded-lg border border-gray-200 bg-white p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Link name</label>
                        <input wire:model="name" id="name" type="text" placeholder="Black Friday Sale"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700">Short link slug</label>
                        <div class="mt-1 flex items-center gap-1 text-sm text-gray-500">
                            <span class="shrink-0">{{ request()->getHost() }}/go/</span>
                            <input wire:model="slug" id="slug" type="text" placeholder="black-friday-sale"
                                class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        </div>
                        @error('slug') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="url" class="block text-sm font-medium text-gray-700">Destination URL</label>
                        <input wire:model="url" id="url" type="url" placeholder="https://example.com/black-friday"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                        @error('url') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description (optional)</label>
                        <textarea wire:model="description" id="description" rows="2" placeholder="Track performance of our Black Friday campaign"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
                        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="mt-6 flex items-center gap-3">
                    <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">Create link</button>
                    <a href="{{ route('dashboard.marketing') }}" class="text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                </div>
            </div>
        </form>
    @endif
</div>
