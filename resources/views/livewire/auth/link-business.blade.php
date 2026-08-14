<div class="min-h-[70vh] flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-lg">
        {{-- Header --}}
        <div class="mb-8">
            <button wire:click="goBack" class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 mb-4">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </button>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Link your website</h1>
            <p class="mt-1 text-sm text-gray-500">Already have a website? Add it here to manage your business from the Ilthres dashboard.</p>
        </div>

        {{-- Messages --}}
        @if ($successMessage)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700">{{ $successMessage }}</div>
        @endif
        @if ($errorMessage)
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ $errorMessage }}</div>
        @endif

        {{-- Pricing notice --}}
        @if ($pricingEnabled && $linkedPlan)
            <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Linked Business Plan</p>
                        <p class="text-xs text-gray-500">Monthly subscription to link your external website</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-gray-900">₦{{ number_format($linkedPlan->price) }}</p>
                        <p class="text-xs text-gray-400">/month</p>
                    </div>
                </div>
            </div>
        @elseif (! $pricingEnabled)
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 p-4">
                <div class="flex items-center gap-2">
                    <svg class="h-4 w-4 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-green-700">Pricing is currently disabled — linking your business is free for now.</p>
                </div>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6">
            {{-- Step 1: Website URL --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex h-8 w-8 items-center justify-center rounded-full {{ $urlVerified ? 'bg-green-100 text-green-600' : 'bg-dark-gold/10 text-dark-gold' }} text-sm font-bold">
                        @if ($urlVerified)
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        @else
                            1
                        @endif
                    </div>
                    <h2 class="text-sm font-semibold text-gray-900">Your website URL</h2>
                </div>

                <div class="flex gap-2">
                    <div class="flex-1 relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                        </div>
                        <input wire:model="websiteUrl" type="text" placeholder="https://example.com"
                            {{ $urlVerified ? 'disabled' : '' }}
                            class="block w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-3 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold @error('websiteUrl') border-red-300 @enderror">
                    </div>
                    @if (! $urlVerified)
                        <button wire:click="verifyUrl" wire:loading.attr="disabled" type="button"
                            class="rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50 whitespace-nowrap">
                            <span wire:loading.remove wire:target="verifyUrl">Verify</span>
                            <span wire:loading wire:target="verifyUrl">Checking...</span>
                        </button>
                    @endif
                </div>
                @error('websiteUrl') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                @if ($urlVerified)
                    <div class="mt-3 flex items-center gap-2 rounded-lg bg-green-50 px-3 py-2 text-sm text-green-700">
                        <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="truncate">{{ $verifiedUrl }}</span>
                    </div>
                @endif
            </div>

            {{-- Step 2: Business details (shown after URL verified) --}}
            @if ($urlVerified)
                <div class="rounded-2xl border border-gray-200 bg-white p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-dark-gold/10 text-dark-gold text-sm font-bold">2</div>
                        <h2 class="text-sm font-semibold text-gray-900">Business details</h2>
                    </div>

                    <div class="space-y-4">
                        {{-- Business name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Business name <span class="text-red-500">*</span></label>
                            <input wire:model="name" id="name" type="text"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold @error('name') border-red-300 @enderror">
                            @if ($fetchedTitle && $fetchedTitle === $name)
                                <p class="mt-1 text-xs text-gray-400">Auto-filled from your website title</p>
                            @endif
                            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Business type --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Business type <span class="text-red-500">*</span></label>
                            <p class="text-xs text-gray-500 mt-0.5">This cannot be changed later.</p>
                            <div class="mt-3 grid grid-cols-2 gap-3">
                                <button wire:click="$set('type', 'product_store')" type="button"
                                    class="rounded-xl border-2 p-3 text-center transition
                                    {{ $type === 'product_store' ? 'border-dark-gold bg-dark-gold/5 ring-1 ring-dark-gold' : 'border-gray-200 hover:border-gray-300' }}">
                                    <span class="text-lg">🛍️</span>
                                    <span class="mt-1 block text-xs font-medium text-gray-900">Products</span>
                                </button>
                                <button wire:click="$set('type', 'service_business')" type="button"
                                    class="rounded-xl border-2 p-3 text-center transition
                                    {{ $type === 'service_business' ? 'border-dark-gold bg-dark-gold/5 ring-1 ring-dark-gold' : 'border-gray-200 hover:border-gray-300' }}">
                                    <span class="text-lg">💼</span>
                                    <span class="mt-1 block text-xs font-medium text-gray-900">Services</span>
                                </button>
                            </div>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        {{-- Category --}}
                        <div>
                            <label for="categoryId" class="block text-sm font-medium text-gray-700">Category</label>
                            <select wire:model="categoryId" id="categoryId"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-dark-gold focus:ring-1 focus:ring-dark-gold">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" wire:loading.attr="disabled"
                    class="w-full rounded-lg bg-dark-gold px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-dark-gold/25 hover:bg-dark-gold-500 hover:shadow-dark-gold/40 disabled:opacity-50">
                    @if ($pricingEnabled)
                        <span wire:loading.remove wire:target="save">Pay ₦{{ number_format($linkedPlan?->price ?? 7500) }} & link business</span>
                    @else
                        <span wire:loading.remove wire:target="save">Link business & go to dashboard</span>
                    @endif
                    <span wire:loading wire:target="save">Processing...</span>
                </button>
            @endif

            {{-- Info note --}}
            @if (! $urlVerified)
                <div class="rounded-xl bg-gray-50 p-4 text-xs text-gray-500 leading-relaxed">
                    <p class="font-medium text-gray-700 mb-1">How it works</p>
                    <ul class="space-y-1 list-disc list-inside">
                        <li>Enter your existing website URL and we'll verify it's live</li>
                        <li>Add your business name and category</li>
                        @if ($pricingEnabled)
                            <li>Pay ₦{{ number_format($linkedPlan?->price ?? 7500) }}/month to link your business</li>
                        @else
                            <li>Link your business for free while pricing is disabled</li>
                        @endif
                        <li>Manage your business details, analytics, and marketing from one place</li>
                    </ul>
                </div>
            @endif
        </form>
    </div>
</div>
