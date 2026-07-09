{{--
  Business settings Livewire component.
  Shows a business selector, then logo/cover upload and theme customization.
--}}
<div class="space-y-8">
    {{-- Business selector --}}
    <div class="rounded-lg border border-gray-200 bg-white p-4">
        <label for="selectedBusinessId" class="block text-sm font-medium text-gray-700">Select business</label>
        <select wire:model="selectedBusinessId" id="selectedBusinessId"
            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
            <option value="">Choose a business</option>
            @foreach ($businesses as $biz)
                <option value="{{ $biz->id }}">{{ $biz->name }} ({{ $biz->isProductStore() ? 'Store' : 'Service' }})</option>
            @endforeach
        </select>
    </div>

    @if (! $business)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Select a business above to manage its settings.</p>
        </div>
        @php return; @endphp
    @endif

    {{-- Success banner. --}}
    @if ($successMessage)
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700 flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ $successMessage }}</span>
            <button wire:click="$set('successMessage', null)" class="ml-auto">&times;</button>
        </div>
    @endif

    @if ($errorMessage)
        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    {{-- ═══ Logo upload ═══ --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Logo</h2>
        <p class="mt-1 text-sm text-gray-500">JPEG, PNG, WebP. Max 2MB.</p>

        <div class="mt-4 flex items-center gap-6">
            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-gray-100">
                @if ($business->logo)
                    <img src="{{ Storage::url($business->logo) }}" alt="Logo" class="h-full w-full object-cover">
                @else
                    <div class="flex h-full items-center justify-center text-2xl font-bold text-gray-400">
                        {{ substr($business->name, 0, 2) }}
                    </div>
                @endif
            </div>

            <div class="flex-1">
                @if ($newLogo)
                    <div class="flex items-center gap-3">
                        <img src="{{ $newLogo->temporaryUrl() }}" alt="Preview" class="h-14 w-14 rounded-lg object-cover">
                        <p class="text-sm text-gray-500">{{ $newLogo->getClientOriginalName() }}</p>
                    </div>
                    <div class="mt-2 flex gap-2">
                        <button wire:click="uploadLogo" class="rounded-lg bg-gray-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Save logo</button>
                        <button wire:click="$set('newLogo', null)" class="text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                    </div>
                @else
                    <label class="cursor-pointer text-sm text-gray-900 hover:text-gray-700">
                        <span class="rounded-lg bg-white border border-gray-300 px-4 py-2 text-xs font-medium hover:bg-gray-50">Choose file</span>
                        <input wire:model="newLogo" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                    </label>
                @endif
                @error('newLogo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- ═══ Cover image upload ═══ --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Cover image</h2>
        <p class="mt-1 text-sm text-gray-500">JPEG, PNG, WebP. Max 5MB.</p>

        <div class="mt-4">
            @if ($business->cover)
                <div class="relative mb-3 overflow-hidden rounded-lg bg-gray-100 h-40">
                    <img src="{{ Storage::url($business->cover) }}" alt="Cover" class="h-full w-full object-cover">
                </div>
            @endif

            @if ($newCover)
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ $newCover->temporaryUrl() }}" alt="Preview" class="h-20 rounded-lg object-cover">
                    <p class="text-sm text-gray-500">{{ $newCover->getClientOriginalName() }}</p>
                </div>
                <div class="flex gap-2">
                    <button wire:click="uploadCover" class="rounded-lg bg-gray-900 px-4 py-1.5 text-xs font-medium text-white hover:bg-gray-800">Save cover</button>
                    <button wire:click="$set('newCover', null)" class="text-xs text-gray-600 hover:text-gray-900">Cancel</button>
                </div>
            @else
                <label class="cursor-pointer">
                    <span class="rounded-lg bg-white border border-gray-300 px-4 py-2 text-xs font-medium hover:bg-gray-50">Choose file</span>
                    <input wire:model="newCover" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                </label>
            @endif
            @error('newCover') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- ═══ Theme selection & customization ═══ --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <h2 class="text-lg font-semibold text-gray-900">Theme</h2>
        <p class="mt-1 text-sm text-gray-500">Choose a theme and customise its colours.</p>

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-3">
            @foreach ($themes as $theme)
                @php $cfg = $theme->config; @endphp
                <button wire:click="selectTheme({{ $theme->id }})" type="button"
                    class="relative rounded-xl border-2 p-4 text-left transition
                    {{ $themeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <div class="flex gap-1 mb-2">
                        <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['primary'] ?? '#4F46E5' }}"></span>
                        <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['secondary'] ?? '#10B981' }}"></span>
                        <span class="h-5 w-5 rounded" style="background:{{ $cfg['colors']['accent'] ?? '#F59E0B' }}"></span>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-900">{{ $theme->name }}</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $theme->description }}</p>
                    @if ($theme->is_premium)
                        <span class="mt-1.5 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Premium</span>
                    @endif
                </button>
            @endforeach
        </div>
        @error('themeId') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

        @if ($themeId)
            <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-5">
                <h3 class="text-sm font-semibold text-gray-900">Customise colours</h3>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Primary</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input wire:model="themePrimary" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                            <input wire:model="themePrimary" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Secondary</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input wire:model="themeSecondary" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                            <input wire:model="themeSecondary" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Background</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input wire:model="themeBackground" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                            <input wire:model="themeBackground" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Text</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input wire:model="themeText" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                            <input wire:model="themeText" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600">Accent</label>
                        <div class="mt-1 flex items-center gap-2">
                            <input wire:model="themeAccent" type="color" class="h-9 w-14 rounded-lg border p-0.5 cursor-pointer">
                            <input wire:model="themeAccent" type="text" maxlength="7" class="w-24 rounded-lg border px-2.5 py-2 text-xs">
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex items-center gap-3 rounded-lg border bg-white p-3">
                    <span class="h-8 w-8 rounded-lg" style="background:{{ $themePrimary }}"></span>
                    <span class="h-8 w-8 rounded-lg" style="background:{{ $themeSecondary }}"></span>
                    <span class="h-8 w-8 rounded-lg border" style="background:{{ $themeBackground }}"></span>
                    <span class="text-xs font-medium" style="color:{{ $themeText }}">Aa</span>
                    <span class="h-8 w-8 rounded-lg" style="background:{{ $themeAccent }}"></span>
                </div>

                <div class="mt-4 flex justify-end">
                    <button wire:click="saveTheme" class="rounded-lg bg-gray-900 px-4 py-2 text-xs font-semibold text-white hover:bg-gray-800">Save theme</button>
                </div>
            </div>
        @endif
    </div>
</div>
