<div class="space-y-6">
    @if (session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 p-3 text-sm text-green-700 flex items-center gap-2">
            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700">{{ session('error') }}</div>
    @endif

    @if (! $verification || $verification->isUnverified())
        {{-- No verification submitted yet — show form --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold text-gray-900">Submit KYC Documents</h2>
            <p class="mt-1 text-sm text-gray-500">Upload the following documents to verify your business. All documents must be clear and legible (JPEG, PNG, or PDF, max 10MB each).</p>

            <form wire:submit="submit" class="mt-6 space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Government-issued ID</label>
                    <p class="text-xs text-gray-500">Passport, driver's license, or national ID card.</p>
                    <input wire:model="governmentId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('governmentId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Business Registration</label>
                    <p class="text-xs text-gray-500">CAC certificate, business registration document, or equivalent.</p>
                    <input wire:model="businessRegistration" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('businessRegistration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Utility Bill</label>
                    <p class="text-xs text-gray-500">Recent utility bill showing business address (electricity, water, internet, etc.).</p>
                    <input wire:model="utilityBill" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('utilityBill') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tax ID</label>
                    <p class="text-xs text-gray-500">Tax identification number or certificate.</p>
                    <input wire:model="taxId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('taxId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                        Submit for verification
                    </button>
                </div>
            </form>
        </div>

    @elseif ($verification->isPending() || $verification->isUnderReview())
        {{-- Awaiting review --}}
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-amber-900">Verification in progress</h3>
            <p class="mt-1 text-sm text-amber-700">Your documents have been submitted and are awaiting review. This usually takes 1-2 business days.</p>
            <p class="mt-2 text-xs text-amber-600">Submitted {{ $verification->submitted_at?->diffForHumans() }}</p>
        </div>

    @elseif ($verification->isVerified())
        {{-- Verified --}}
        <div class="rounded-lg border border-green-200 bg-green-50 p-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-green-900">Business verified</h3>
            <p class="mt-1 text-sm text-green-700">Your business has been verified. The verified badge is now visible on your public profile.</p>
            @if ($verification->reviewed_at)
                <p class="mt-2 text-xs text-green-600">Reviewed {{ $verification->reviewed_at->diffForHumans() }}</p>
            @endif
        </div>

    @elseif ($verification->isRejected())
        {{-- Rejected --}}
        <div class="rounded-lg border border-red-200 bg-red-50 p-6">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-red-900">Verification rejected</h3>
                    @if ($verification->admin_notes)
                        <p class="mt-1 text-sm text-red-700"><strong>Reason:</strong> {{ $verification->admin_notes }}</p>
                    @endif
                    <p class="mt-2 text-xs text-red-600">Please correct the issues and resubmit your documents.</p>
                </div>
            </div>
        </div>

        {{-- Resubmit form --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h3 class="text-sm font-semibold text-gray-900">Resubmit documents</h3>
            <form wire:submit="submit" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Government-issued ID</label>
                    <input wire:model="governmentId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('governmentId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Business Registration</label>
                    <input wire:model="businessRegistration" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('businessRegistration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Utility Bill</label>
                    <input wire:model="utilityBill" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('utilityBill') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Tax ID</label>
                    <input wire:model="taxId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('taxId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                        Submit for verification
                    </button>
                </div>
            </form>
        </div>

    @elseif ($verification->isPending() || $verification->isUnderReview())
        {{-- Awaiting review --}}
        <div class="rounded-lg border border-amber-200 bg-amber-50 p-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-amber-100">
                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-amber-900">Verification in progress</h3>
            <p class="mt-1 text-sm text-amber-700">Your documents have been submitted and are awaiting review. This usually takes 1-2 business days.</p>
            <p class="mt-2 text-xs text-amber-600">Submitted {{ $verification->submitted_at?->diffForHumans() }}</p>
        </div>

    @elseif ($verification->isVerified())
        {{-- Verified --}}
        <div class="rounded-lg border border-green-200 bg-green-50 p-6 text-center">
            <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-lg font-semibold text-green-900">Business verified</h3>
            <p class="mt-1 text-sm text-green-700">Your business is verified. The verified badge is visible on your public profile.</p>
            @if ($verification->reviewed_at)
                <p class="mt-2 text-xs text-green-600">Reviewed {{ $verification->reviewed_at->diffForHumans() }}</p>
            @endif
        </div>

    @elseif ($verification->isRejected())
        {{-- Rejected --}}
        <div class="rounded-lg border border-red-200 bg-red-50 p-6">
            <div class="flex items-start gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100">
                    <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-red-900">Verification rejected</h3>
                    @if ($verification->admin_notes)
                        <p class="mt-1 text-sm text-red-700"><strong>Reason:</strong> {{ $verification->admin_notes }}</p>
                    @endif
                    <p class="mt-2 text-xs text-red-600">Please correct the issues and resubmit your documents below.</p>
                </div>
            </div>
        </div>

        {{-- Resubmit form --}}
        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <h3 class="text-sm font-semibold text-gray-900">Resubmit documents</h3>
            <form wire:submit="submit" class="mt-4 space-y-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Government-issued ID</label>
                    <input wire:model="governmentId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('governmentId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Business Registration</label>
                    <input wire:model="businessRegistration" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('businessRegistration') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Utility Bill</label>
                    <input wire:model="utilityBill" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('utilityBill') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600">Tax ID</label>
                    <input wire:model="taxId" type="file" accept="image/jpeg,image/png,application/pdf" class="mt-1 block w-full text-sm">
                    @error('taxId') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="rounded-lg bg-gray-900 px-6 py-2.5 text-sm font-semibold text-white hover:bg-gray-800">
                        Submit for verification
                    </button>
                </div>
            </form>
        </div>
    @endif
</div>
