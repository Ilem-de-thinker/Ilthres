{{--
  Theme customizer — color pickers, font selectors, radius slider.
  Business owners can customise their active theme's appearance here.
--}}
<div class="rounded-xl border border-gray-200 bg-white p-6">
    @if ($successMessage)
        <div class="mb-4 rounded-lg bg-green-50 border border-green-200 p-3 text-sm text-green-700">{{ $successMessage }}</div>
    @endif
    @if ($errorMessage)
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
    @endif

    {{-- Theme selector. --}}
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-2">Select theme</label>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
            @foreach ($themes as $theme)
                <button wire:click="selectTheme({{ $theme->id }})" type="button"
                    class="rounded-lg border p-3 text-left text-sm transition
                        {{ $selectedThemeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="block font-medium text-gray-900">{{ $theme->name }}</span>
                    @if ($theme->is_premium)
                        <span class="text-[10px] text-yellow-600">Premium</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Color controls. --}}
    @if ($selectedThemeId)
        <div class="space-y-6">
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Colors</h4>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach ([
                        ['label' => 'Primary', 'field' => 'primary'],
                        ['label' => 'Secondary', 'field' => 'secondary'],
                        ['label' => 'Background', 'field' => 'background'],
                        ['label' => 'Text', 'field' => 'text'],
                        ['label' => 'Accent', 'field' => 'accent'],
                    ] as $color)
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">{{ $color['label'] }}</label>
                            <div class="flex items-center gap-2">
                                <input wire:model="{{ $color['field'] }}" type="color" class="h-8 w-10 rounded border p-0.5 cursor-pointer">
                                <input wire:model="{{ $color['field'] }}" type="text" maxlength="7" class="w-20 rounded border px-2 py-1 text-xs">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-3">Typography</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Heading font</label>
                        <input wire:model="headingFont" type="text" class="w-full rounded-lg border px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600 mb-1">Body font</label>
                        <input wire:model="bodyFont" type="text" class="w-full rounded-lg border px-3 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Border radius: {{ $radius }}</label>
                    <input wire:model="radius" type="text" class="w-full rounded-lg border px-3 py-2 text-sm">
                </div>
                <div class="flex items-center">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="animations" type="checkbox" class="rounded border-gray-300">
                        <span class="text-sm text-gray-700">Enable animations</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button wire:click="saveCustomization"
                    class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800">
                    Save theme
                </button>
            </div>
        </div>
    @endif
</div>
