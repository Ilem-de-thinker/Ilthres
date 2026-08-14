{{--
  Theme customizer — color pickers, font selectors, radius options.
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
        <label class="block text-sm font-medium text-gray-900 mb-1">Select theme</label>
        <p class="text-xs text-gray-500 mb-3">Choose a base theme. You can customise colors, fonts, and shape below.</p>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
            @foreach ($themes as $theme)
                <button wire:click="selectTheme({{ $theme->id }})" type="button"
                    class="rounded-lg border p-3 text-left text-sm transition
                        {{ $selectedThemeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="block font-medium text-gray-900">{{ $theme->name }}</span>
                    @if ($theme->description)
                        <span class="mt-0.5 block text-[11px] leading-tight text-gray-500 line-clamp-2">{{ $theme->description }}</span>
                    @endif
                    @if ($theme->is_premium)
                        <span class="mt-1 inline-block rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-medium text-amber-700">Premium</span>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    {{-- Customisation controls. --}}
    @if ($selectedThemeId)
        <div class="space-y-6 border-t border-gray-100 pt-6">

            {{-- Colors --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Colors</h4>
                <p class="text-xs text-gray-500 mb-3">Pick the main colors for your store page.</p>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                    @foreach ([
                        ['label' => 'Primary', 'field' => 'primary', 'hint' => 'Buttons, links, key elements'],
                        ['label' => 'Secondary', 'field' => 'secondary', 'hint' => 'Supporting elements'],
                        ['label' => 'Background', 'field' => 'background', 'hint' => 'Page background'],
                        ['label' => 'Text', 'field' => 'text', 'hint' => 'Main body text'],
                        ['label' => 'Accent', 'field' => 'accent', 'hint' => 'Highlights, badges'],
                    ] as $color)
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">{{ $color['label'] }}</label>
                            <div class="flex items-center gap-2">
                                <input wire:model.live="{{ $color['field'] }}" type="color" class="h-9 w-10 rounded border border-gray-300 p-0.5 cursor-pointer">
                                <input wire:model.live="{{ $color['field'] }}" type="text" maxlength="7" class="w-20 rounded border border-gray-300 px-2 py-1.5 text-xs font-mono">
                            </div>
                            <p class="mt-0.5 text-[10px] text-gray-400">{{ $color['hint'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Typography --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Typography</h4>
                <p class="text-xs text-gray-500 mb-3">Choose the fonts used across your store.</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Headings</label>
                        <select wire:model.live="headingFont"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            @foreach ($availableFonts as $font)
                                <option value="{{ $font }}">{{ $font }}</option>
                            @endforeach
                        </select>
                        <p class="mt-0.5 text-[10px] text-gray-400">Used for titles and section headers</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Body text</label>
                        <select wire:model.live="bodyFont"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            @foreach ($availableFonts as $font)
                                <option value="{{ $font }}">{{ $font }}</option>
                            @endforeach
                        </select>
                        <p class="mt-0.5 text-[10px] text-gray-400">Used for paragraphs and descriptions</p>
                    </div>
                </div>
            </div>

            {{-- Shape & motion --}}
            <div>
                <h4 class="text-sm font-semibold text-gray-900 mb-1">Shape & motion</h4>
                <p class="text-xs text-gray-500 mb-3">Control how round or sharp elements appear, and whether hover effects are enabled.</p>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Corner style</label>
                        <select wire:model.live="radius"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            <option value="0rem">Sharp (square corners)</option>
                            <option value="0.125rem">Subtle</option>
                            <option value="0.25rem">Small</option>
                            <option value="0.5rem">Medium</option>
                            <option value="1rem">Large (pill-like)</option>
                            <option value="9999px">Fully rounded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Animations</label>
                        <select wire:model.live="animations"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                            <option value="1">Enabled (smooth hover effects)</option>
                            <option value="0">Disabled (no animations)</option>
                        </select>
                        <p class="mt-0.5 text-[10px] text-gray-400">Disable for a more static experience</p>
                    </div>
                </div>
            </div>

            {{-- Save --}}
            <div class="flex justify-end pt-4 border-t border-gray-100">
                <button wire:click="saveCustomization"
                    class="rounded-lg bg-gray-900 px-6 py-2 text-sm font-semibold text-white hover:bg-gray-800 transition">
                    Save theme
                </button>
            </div>
        </div>
    @endif
</div>
