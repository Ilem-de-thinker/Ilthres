{{--
  Subscription status card — shows current plan, usage summary, upgrade/downgrade links.
--}}
<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to see subscription details.</p>
        </div>
    @else
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

            @if ($plan)
                <div class="mt-6 flex flex-wrap gap-3">
                    @if ($upgradePlan)
                        <a href="{{ route('dashboard.pricing') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Upgrade to {{ $upgradePlan->name }}</a>
                    @endif
                    @if ($downgradePlan)
                        <a href="{{ route('dashboard.pricing') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-900 hover:bg-gray-50">Downgrade</a>
                    @endif
                </div>
            @else
                <div class="mt-6">
                    <a href="{{ route('dashboard.pricing') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Choose a plan</a>
                </div>
            @endif
        </div>
    @endif
</div>
