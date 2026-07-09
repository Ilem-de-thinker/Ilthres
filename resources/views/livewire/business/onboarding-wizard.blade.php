{{--
  Multi-step onboarding wizard.
  Steps: WhatsApp → Business Type → Pricing → Setup (name, category, theme, logo) → Dashboard.
  Progress is persisted to the user record for cross-session recovery.
--}}
<div class="mx-auto max-w-2xl px-4 py-12 sm:px-6">
    {{-- Global error banner (e.g. business creation failure). --}}
    @if ($errorMessage)
        <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $errorMessage }}</span>
            <button wire:click="$set('errorMessage', null)" class="ml-auto text-red-500 hover:text-red-700">&times;</button>
        </div>
    @endif

    {{-- Global success banner (e.g. business created, redirecting). --}}
    @if ($successMessage)
        <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700 flex items-start gap-3">
            <svg class="mt-0.5 h-5 w-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ $successMessage }}</span>
        </div>
    @endif

    {{-- Progress indicator. --}}
    <nav aria-label="Onboarding progress" class="mb-12">
        <ol class="flex items-center justify-center gap-2 text-sm font-medium text-gray-500">
            <li class="flex items-center gap-2">
                <span class="{{ $step >= 1 ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }} flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold">1</span>
                <span class="hidden sm:inline {{ $step >= 1 ? 'text-gray-900' : '' }}">WhatsApp</span>
            </li>
            <li class="h-px w-10 bg-gray-200"></li>
            <li class="flex items-center gap-2">
                <span class="{{ $step >= 2 ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }} flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold">2</span>
                <span class="hidden sm:inline {{ $step >= 2 ? 'text-gray-900' : '' }}">Type</span>
            </li>
            <li class="h-px w-10 bg-gray-200"></li>
            <li class="flex items-center gap-2">
                <span class="{{ $step >= 3 ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }} flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold">3</span>
                <span class="hidden sm:inline {{ $step >= 3 ? 'text-gray-900' : '' }}">Pricing</span>
            </li>
            <li class="h-px w-10 bg-gray-200"></li>
            <li class="flex items-center gap-2">
                <span class="{{ $step >= 4 ? 'bg-gray-900 text-white' : 'bg-gray-100 text-gray-500' }} flex h-8 w-8 items-center justify-center rounded-full text-xs font-bold">4</span>
                <span class="hidden sm:inline {{ $step >= 4 ? 'text-gray-900' : '' }}">Setup</span>
            </li>
        </ol>
    </nav>

    {{-- ═══════════════════════════════════════════════════
         Step 1: WhatsApp number
         ═══════════════════════════════════════════════════ --}}
    @if ($step === 1)
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Link your WhatsApp</h1>
            <p class="mt-2 text-sm text-gray-600">Customers will use this number to chat with you directly from your business profile. You can change it later.</p>

            <div class="mt-8 space-y-6">
                <div>
                    <label for="whatsapp" class="block text-sm font-medium text-gray-700">WhatsApp number</label>
                    <div class="mt-1 relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">+234</span>
                        <input wire:model="whatsapp" id="whatsapp" type="tel" placeholder="8012345678" required
                            class="block w-full rounded-lg border border-gray-300 pl-14 pr-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900
                            @error('whatsapp') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror">
                    </div>
                    @error('whatsapp') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="mt-2 text-xs text-gray-400">Enter the number without the country code. We'll format it automatically.</p>
                </div>

                <div class="flex justify-end">
                    <button wire:click="nextStep" wire:loading.attr="disabled"
                        class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                        Continue
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Step 2: Business type
         ═══════════════════════════════════════════════════ --}}
    @if ($step === 2)
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">What type of business?</h1>
            <p class="mt-2 text-sm text-gray-600">Choose the option that best describes what you offer. <strong>This cannot be changed later.</strong></p>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-2">
                {{-- Product store card --}}
                <button wire:click="$set('businessType', 'product_store')" type="button"
                    class="relative rounded-xl border-2 p-6 text-left transition
                    {{ $businessType === 'product_store' ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="text-3xl">🛍️</span>
                    <h3 class="mt-3 text-lg font-semibold text-gray-900">Product store</h3>
                    <p class="mt-1 text-sm text-gray-500">Sell physical or digital products — fashion, electronics, groceries, and more.</p>
                    <ul class="mt-3 space-y-1.5 text-sm text-gray-500">
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Product listings with images &amp; variants</li>
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Pricing &amp; discounts</li>
                    </ul>
                </button>

                {{-- Service business card --}}
                <button wire:click="$set('businessType', 'service_business')" type="button"
                    class="relative rounded-xl border-2 p-6 text-left transition
                    {{ $businessType === 'service_business' ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                    <span class="text-3xl">💼</span>
                    <h3 class="mt-3 text-lg font-semibold text-gray-900">Service business</h3>
                    <p class="mt-1 text-sm text-gray-500">Offer services to customers — salon, clinic, consulting, plumbing, and more.</p>
                    <ul class="mt-3 space-y-1.5 text-sm text-gray-500">
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Service listings with duration</li>
                        <li class="flex items-center gap-2"><svg class="h-3.5 w-3.5 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Appointment booking (coming soon)</li>
                    </ul>
                </button>
            </div>
            @error('businessType') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-8 flex items-center justify-between">
                <button wire:click="prevStep" class="text-sm font-medium text-gray-600 hover:text-gray-900">&larr; Back</button>
                <button wire:click="nextStep" wire:loading.attr="disabled"
                    class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                    Continue
                </button>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Step 3: Pricing plan
         ═══════════════════════════════════════════════════ --}}
    @if ($step === 3)
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Choose your plan</h1>
            <p class="mt-2 text-sm text-gray-600">Start free and upgrade anytime. No credit card required for the Free plan.</p>

            <div class="mt-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                @forelse ($plans as $plan)
                    <button wire:click="$set('selectedPlanId', {{ $plan->id }})" type="button" wire:key="plan-{{ $plan->id }}"
                        class="relative rounded-xl border-2 p-6 text-left transition
                        {{ $selectedPlanId === $plan->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                        <h3 class="text-base font-semibold text-gray-900">{{ $plan->name }}</h3>
                        <p class="mt-1 text-2xl font-bold text-gray-900">
                            @if ((float) $plan->price === 0.0)
                                Free
                            @else
                                ₦{{ number_format((float) $plan->price) }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500">/{{ $plan->interval }}</p>
                        <ul class="mt-4 space-y-2">
                            @foreach ($plan->features as $feature)
                                <li class="flex items-start gap-2 text-xs text-gray-600">
                                    <svg class="mt-0.5 h-3.5 w-3.5 shrink-0 text-gray-900" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </button>
                @empty
                    <p class="col-span-3 text-sm text-gray-500">No plans available at the moment.</p>
                @endforelse
            </div>
            @error('selectedPlanId') <p class="mt-3 text-sm text-red-600">{{ $message }}</p> @enderror

            <div class="mt-8 flex items-center justify-between">
                <button wire:click="prevStep" class="text-sm font-medium text-gray-600 hover:text-gray-900">&larr; Back</button>
                <button wire:click="nextStep" wire:loading.attr="disabled"
                    class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                    Continue
                </button>
            </div>
        </div>
    @endif

    {{-- ═══════════════════════════════════════════════════
         Step 4: Business setup — name, category, theme, logo
         ═══════════════════════════════════════════════════ --}}
    @if ($step === 4)
        <div class="rounded-xl border border-gray-200 bg-white p-8">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900">Set up your business</h1>
            <p class="mt-2 text-sm text-gray-600">Tell us about your business. You can change these details later from your dashboard.</p>

            <div class="mt-8 space-y-8">
                {{-- Business name --}}
                <div>
                    <label for="businessName" class="block text-sm font-medium text-gray-700">Business name <span class="text-red-500">*</span></label>
                    <input wire:model="businessName" id="businessName" type="text" required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900
                        @error('businessName') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror"
                        placeholder="e.g. Ade's Fashion Store">
                    @error('businessName') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="businessDescription" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea wire:model="businessDescription" id="businessDescription" rows="3"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900"
                        placeholder="Tell customers what you offer..."></textarea>
                    @error('businessDescription') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
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
                    @error('categoryId') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- ═══ Theme selection ═══ --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700">Choose a theme <span class="text-red-500">*</span></label>
                    <p class="text-xs text-gray-500 mt-0.5">Pick a visual style for your business page. You can customise the colours below.</p>

                    <div class="mt-3 grid grid-cols-1 gap-3 sm:grid-cols-3">
                        @forelse ($themes as $theme)
                            @php
                                $colors = $theme->config['colors'] ?? [];
                            @endphp
                            <button wire:click="selectTheme({{ $theme->id }})" type="button" wire:key="theme-{{ $theme->id }}"
                                class="relative rounded-xl border-2 p-4 text-left transition
                                {{ $themeId === $theme->id ? 'border-gray-900 bg-gray-50 ring-1 ring-gray-900' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex gap-1 mb-2">
                                    <span class="h-5 w-5 rounded" style="background:{{ $colors['primary'] ?? '#4F46E5' }}"></span>
                                    <span class="h-5 w-5 rounded" style="background:{{ $colors['secondary'] ?? '#10B981' }}"></span>
                                    <span class="h-5 w-5 rounded" style="background:{{ $colors['accent'] ?? '#F59E0B' }}"></span>
                                </div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ $theme->name }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">{{ $theme->description }}</p>
                                @if ($theme->is_premium)
                                    <span class="mt-1.5 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700">Premium</span>
                                @endif
                            </button>
                        @empty
                            <p class="col-span-3 text-sm text-gray-500">No themes are available for your business type.</p>
                        @endforelse
                    </div>
                    @error('themeId') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- ═══ Theme color customizer ═══ --}}
                @if ($themeId)
                    <div class="rounded-lg border border-gray-200 bg-gray-50 p-5">
                        <h3 class="text-sm font-semibold text-gray-900">Customize colours</h3>
                        <p class="text-xs text-gray-500 mt-0.5">These colours will be applied to your business page theme.</p>

                        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                            {{-- Primary --}}
                            <div>
                                <label for="themePrimary" class="block text-xs font-medium text-gray-600">Primary</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input wire:model="themePrimary" id="themePrimary" type="color"
                                        class="h-9 w-14 rounded-lg border border-gray-300 p-0.5 cursor-pointer">
                                    <input wire:model="themePrimary" type="text" maxlength="7"
                                        class="w-24 rounded-lg border border-gray-300 px-2.5 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>
                            </div>

                            {{-- Secondary --}}
                            <div>
                                <label for="themeSecondary" class="block text-xs font-medium text-gray-600">Secondary</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input wire:model="themeSecondary" id="themeSecondary" type="color"
                                        class="h-9 w-14 rounded-lg border border-gray-300 p-0.5 cursor-pointer">
                                    <input wire:model="themeSecondary" type="text" maxlength="7"
                                        class="w-24 rounded-lg border border-gray-300 px-2.5 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>
                            </div>

                            {{-- Background --}}
                            <div>
                                <label for="themeBackground" class="block text-xs font-medium text-gray-600">Background</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input wire:model="themeBackground" id="themeBackground" type="color"
                                        class="h-9 w-14 rounded-lg border border-gray-300 p-0.5 cursor-pointer">
                                    <input wire:model="themeBackground" type="text" maxlength="7"
                                        class="w-24 rounded-lg border border-gray-300 px-2.5 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>
                            </div>

                            {{-- Text --}}
                            <div>
                                <label for="themeText" class="block text-xs font-medium text-gray-600">Text</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input wire:model="themeText" id="themeText" type="color"
                                        class="h-9 w-14 rounded-lg border border-gray-300 p-0.5 cursor-pointer">
                                    <input wire:model="themeText" type="text" maxlength="7"
                                        class="w-24 rounded-lg border border-gray-300 px-2.5 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>
                            </div>

                            {{-- Accent --}}
                            <div>
                                <label for="themeAccent" class="block text-xs font-medium text-gray-600">Accent</label>
                                <div class="mt-1 flex items-center gap-2">
                                    <input wire:model="themeAccent" id="themeAccent" type="color"
                                        class="h-9 w-14 rounded-lg border border-gray-300 p-0.5 cursor-pointer">
                                    <input wire:model="themeAccent" type="text" maxlength="7"
                                        class="w-24 rounded-lg border border-gray-300 px-2.5 py-2 text-xs shadow-sm focus:border-gray-900 focus:ring-1 focus:ring-gray-900">
                                </div>
                            </div>
                        </div>

                        {{-- Live preview swatch. --}}
                        <div class="mt-4 flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-3">
                            <span class="h-8 w-8 rounded-lg" style="background:{{ $themePrimary }}"></span>
                            <span class="h-8 w-8 rounded-lg" style="background:{{ $themeSecondary }}"></span>
                            <span class="h-8 w-8 rounded-lg border" style="background:{{ $themeBackground }}"></span>
                            <span class="text-xs font-medium" style="color:{{ $themeText }}">Aa</span>
                            <span class="h-8 w-8 rounded-lg" style="background:{{ $themeAccent }}"></span>
                            <span class="text-xs text-gray-400 ml-auto">Live preview</span>
                        </div>
                    </div>
                @endif

                {{-- ═══ Logo upload ═══ --}}
                <div>
                    <label for="logo" class="block text-sm font-medium text-gray-700">Logo <span class="text-xs text-gray-400">(optional)</span></label>
                    <p class="text-xs text-gray-500 mt-0.5">Upload your business logo. JPEG, PNG, WebP. Max 2MB.</p>

                    <div class="mt-2">
                        @if ($logo)
                            <div class="flex items-center gap-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                                <img src="{{ $logo->temporaryUrl() }}" alt="Logo preview" class="h-16 w-16 rounded-lg object-cover">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $logo->getClientOriginalName() }}</p>
                                    <p class="text-xs text-gray-500">{{ number_format($logo->getSize() / 1024, 1) }} KB</p>
                                </div>
                                <button wire:click="removeLogo" type="button" class="text-sm text-red-600 hover:text-red-800">Remove</button>
                            </div>
                        @else
                            <label class="flex cursor-pointer flex-col items-center justify-center rounded-lg border-2 border-dashed border-gray-300 bg-white px-6 py-8 hover:border-gray-400 transition">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                </svg>
                                <p class="mt-2 text-sm text-gray-600"><span class="font-medium text-gray-900">Click to upload</span> or drag and drop</p>
                                <p class="text-xs text-gray-400">PNG, JPG, WebP up to 2MB</p>
                                <input wire:model="logo" id="logo" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only">
                            </label>
                        @endif
                    </div>
                    @error('logo') <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Step 4 navigation + submit. --}}
            <div class="mt-8 flex items-center justify-between">
                <button wire:click="prevStep" class="text-sm font-medium text-gray-600 hover:text-gray-900">&larr; Back</button>
                <button wire:click="complete" wire:loading.attr="disabled"
                    class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800 disabled:opacity-50">
                    Complete setup
                </button>
            </div>
        </div>
    @endif
</div>
