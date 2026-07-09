{{--
  Create business Livewire component — for multi-business users.
  Allows type selection, name, category, theme, and logo upload.
--}}
<div>
    @if ($successMessage)
        <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ $successMessage }}</div>
    @endif
    @if ($errorMessage)
        <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    <form wire:submit="save" class="space-y-6">
        {{-- Business type --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Business type <span class="text-red-500">*</span></label>
            <p class="text-xs text-gray-500 mt-0.5">This cannot be changed later.</p>
            <div class="mt-3 grid grid-cols-1 gap-4 sm:grid-cols-2">
                <button wire:click="$set('type', 'product_store')" type="button"
                    class="rounded-xl border-2 p-4 text-center transition
                    {{ $type === 'product_store' ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="text-2xl">🛍️</span>
                    <span class="mt-1 block text-sm font-medium text-gray-900">Product store</span>
                </button>
                <button wire:click="$set('type', 'service_business')" type="button"
                    class="rounded-xl border-2 p-4 text-center transition
                    {{ $type === 'service_business' ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="text-2xl">💼</span>
                    <span class="mt-1 block text-sm font-medium text-gray-900">Service business</span>
                </button>
            </div>
            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Business name --}}
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Business name <span class="text-red-500">*</span></label>
            <input wire:model="name" id="name" type="text" required
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900
                @error('name') border-red-300 @enderror">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Description --}}
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea wire:model="description" id="description" rows="3"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"></textarea>
        </div>

        {{-- Category --}}
        <div>
            <label for="categoryId" class="block text-sm font-medium text-gray-700">Category</label>
            <select wire:model="categoryId" id="categoryId"
                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Theme --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Theme <span class="text-red-500">*</span></label>
            <div class="mt-2 grid grid-cols-1 gap-3 sm:grid-cols-3">
                @foreach ($themes as $theme)
                    @php $cfg = $theme->config; @endphp
                    <button wire:click="selectTheme({{ $theme->id }})" type="button"
                        class="rounded-xl border-2 p-3 text-left transition
                        {{ $themeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                        <div class="flex gap-1 mb-1">
                            <span class="h-4 w-4 rounded" style="background:{{ $cfg['colors']['primary'] }}"></span>
                            <span class="h-4 w-4 rounded" style="background:{{ $cfg['colors']['secondary'] }}"></span>
                            <span class="h-4 w-4 rounded" style="background:{{ $cfg['colors']['accent'] }}"></span>
                        </div>
                        <h3 class="text-xs font-semibold">{{ $theme->name }}</h3>
                    </button>
                @endforeach
            </div>
            @error('themeId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Logo --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Logo <span class="text-xs text-gray-400">(optional)</span></label>
            @if ($logo)
                <div class="mt-2 flex items-center gap-3 rounded-lg border bg-gray-50 p-3">
                    <img src="{{ $logo->temporaryUrl() }}" alt="Preview" class="h-12 w-12 rounded-lg object-cover">
                    <span class="text-sm">{{ $logo->getClientOriginalName() }}</span>
                    <button wire:click="removeLogo" type="button" class="ml-auto text-sm text-red-600">Remove</button>
                </div>
            @else
                <label class="mt-2 flex cursor-pointer items-center justify-center rounded-lg border-2 border-dashed border-gray-300 px-4 py-6 hover:border-gray-400">
                    <span class="text-sm text-gray-600">Click to upload</span>
                    <input wire:model="logo" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                </label>
            @endif
            @error('logo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
            Create business
        </button>
    </form>
</div>
