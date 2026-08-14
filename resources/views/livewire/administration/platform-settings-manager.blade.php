<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold tracking-tight text-gray-900">Platform Settings</h1>
        <p class="mt-1 text-sm text-gray-500">Manage global platform configuration.</p>
    </div>

    {{-- Pricing toggle --}}
    <div class="rounded-lg border border-gray-200 bg-white p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-sm font-semibold text-gray-900">Pricing & Paid Features</h2>
                <p class="mt-0.5 text-sm text-gray-500">
                    When enabled, businesses must have an active paid subscription to access premium features.
                    When disabled, all features are free for all businesses.
                </p>
                <p class="mt-1 text-xs text-gray-400">Last toggled: {{ $lastToggledAt }}</p>
            </div>
            <button wire:click="togglePricing" wire:confirm="Are you sure you want to {{ $pricingEnabled ? 'disable' : 'enable' }} pricing?{{ $pricingEnabled ? '' : ' All business owners will be notified.' }}"
                class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-900 focus:ring-offset-2 {{ $pricingEnabled ? 'bg-gray-900' : 'bg-gray-200' }}"
                role="switch" aria-checked="{{ $pricingEnabled ? 'true' : 'false' }}">
                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $pricingEnabled ? 'translate-x-5' : 'translate-x-0' }}"></span>
            </button>
        </div>

        <div class="mt-4 rounded-lg {{ $pricingEnabled ? 'bg-green-50 border border-green-200' : 'bg-amber-50 border border-amber-200' }} px-4 py-3">
            <div class="flex items-center gap-2">
                @if ($pricingEnabled)
                    <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-sm font-medium text-green-700">Pricing is enabled — businesses must subscribe to access premium features.</span>
                @else
                    <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <span class="text-sm font-medium text-amber-700">Pricing is disabled — all features are currently free for all businesses.</span>
                @endif
            </div>
        </div>
    </div>
</div>
