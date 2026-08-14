{{--
  Subscription status card — shows current plan, usage summary, upgrade/downgrade links.
--}}
<div>
    @php
        $pricingEnabled = \App\Models\PlatformSetting::get('pricing_enabled', true);
    @endphp

    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see subscription details.</p>
        </div>
    @else
        @if (! $pricingEnabled)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="text-sm font-medium text-green-700">Pricing is temporarily disabled</p>
                        <p class="text-xs text-green-600">All features are currently free. No payment required right now.</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-lg border border-gray-200 bg-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Current plan</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $plan?->name ?? 'Free' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Status</p>
                    <p class="mt-1 text-sm font-medium {{ $subscription?->isActive() ? 'text-green-600' : 'text-red-600' }}">
                        {{ $subscription?->isActive() ? 'Active' : ($subscription ? ucfirst($subscription->status) : 'Trial') }}
                    </p>
                </div>
            </div>

            @if ($subscription?->ends_at)
                <p class="mt-3 text-xs text-gray-400">
                    @if ($subscription->isActive())
                        Renews {{ $subscription->ends_at->format('M j, Y') }}
                    @else
                        Expired {{ $subscription->ends_at->format('M j, Y') }}
                    @endif
                </p>
            @endif

            @if ($scheduledPlan)
                <p class="mt-2 text-xs text-amber-600">
                    Scheduled change to <strong>{{ $scheduledPlan->name }}</strong> on {{ $subscription->ends_at->format('M j, Y') }}.
                </p>
            @endif

            @if ($plan)
                <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                    @foreach (collect($limits)->take(4) as $key => $value)
                        <div>
                            <p class="text-xs text-gray-400">{{ Str::title(str_replace('_', ' ', $key)) }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}</p>
                        </div>
                    @endforeach
                </div>
            @endif

            @if ($plan && $pricingEnabled && ! ($isLinkedBusiness ?? false))
                <div class="mt-6 flex flex-wrap gap-3">
                    @if ($upgradePlan)
                        <a href="{{ route('dashboard.pricing') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Upgrade to {{ $upgradePlan->name }}</a>
                    @endif
                    @if ($downgradePlan)
                        <a href="{{ route('dashboard.pricing') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50">Downgrade</a>
                    @endif
                </div>
            @elseif ($isLinkedBusiness ?? false)
                <div class="mt-6">
                    <p class="text-xs text-gray-400">Linked businesses cannot change plans from here.</p>
                    <a href="{{ route('dashboard') }}" class="mt-2 inline-block rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Go to dashboard</a>
                </div>
            @elseif (! $pricingEnabled && $plan)
                <div class="mt-6">
                    <a href="{{ route('dashboard') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Go to dashboard</a>
                </div>
            @elseif (! $plan)
                <div class="mt-6">
                    <a href="{{ route('dashboard.pricing') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Choose a plan</a>
                </div>
            @endif
        </div>
    @endif
</div>
