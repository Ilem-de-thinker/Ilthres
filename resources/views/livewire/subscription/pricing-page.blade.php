{{--
  Pricing page — lists all active subscription plans with feature comparison.
  Authenticated users see their current plan highlighted with upgrade/downgrade options.
--}}
<div>
    <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-2xl text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900">Pricing</h1>
            <p class="mt-2 text-lg text-gray-600">Choose the plan that fits your business. Upgrade anytime.</p>
        </div>

        @php
            $user = auth()->user();
            $currentPlanSlug = null;
            if ($user) {
                $business = $user->businesses()->first();
                $currentPlanSlug = $business?->subscription?->plan?->slug;
            }
        @endphp

        <div class="mt-12 grid grid-cols-1 gap-8 lg:grid-cols-3">
            @foreach ($plans as $plan)
                @php
                    $isCurrent = $currentPlanSlug === $plan->slug;
                    $isUpgrade = $user && $currentPlanSlug && $plan->sort_order > $plans->firstWhere('slug', $currentPlanSlug)?->sort_order;
                    $isDowngrade = $user && $currentPlanSlug && $plan->sort_order < $plans->firstWhere('slug', $currentPlanSlug)?->sort_order;
                @endphp

                <div class="relative rounded-xl border bg-white p-8 {{ $isCurrent ? 'border-2 border-gray-900 shadow-lg' : 'border-gray-200' }}">
                    @if ($isCurrent)
                        <span class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-gray-900 px-4 py-1 text-xs font-semibold text-white">Current plan</span>
                    @endif

                    <h3 class="text-lg font-semibold text-gray-900">{{ $plan->name }}</h3>
                    <p class="mt-2 text-sm text-gray-500">{{ $plan->description }}</p>

                    <p class="mt-4">
                        <span class="text-4xl font-bold text-gray-900">{{ number_format($plan->price) }}</span>
                        <span class="text-sm text-gray-500">/{{ $plan->interval }}</span>
                    </p>

                    <ul class="mt-6 space-y-3">
                        @foreach (collect($plan->features)->take(6) as $feature)
                            <li class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="h-4 w-4 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ $feature }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-8">
                        @if (! $user)
                            <a href="{{ route('register') }}" class="block w-full rounded-lg border border-gray-300 bg-white px-6 py-3 text-center text-sm font-semibold text-gray-900 hover:bg-gray-50">Get started</a>
                        @elseif ($isCurrent)
                            <a href="{{ route('dashboard') }}" class="block w-full rounded-lg bg-gray-900 px-6 py-3 text-center text-sm font-semibold text-white">Go to dashboard</a>
                        @elseif (! $currentPlanSlug)
                            <a href="{{ route('onboarding') }}" class="block w-full rounded-lg bg-gray-900 px-6 py-3 text-center text-sm font-semibold text-white hover:bg-gray-800">Choose plan</a>
                        @else
                            <button type="button" wire:click="selectPlan({{ $plan->id }})" class="block w-full rounded-lg {{ $isUpgrade ? 'bg-gray-900 text-white hover:bg-gray-800' : 'border border-gray-300 bg-white text-gray-900 hover:bg-gray-50' }} px-6 py-3 text-center text-sm font-semibold">
                                {{ $isUpgrade ? 'Upgrade' : 'Downgrade' }}
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($plans->isEmpty())
            <p class="mt-12 text-center text-sm text-gray-400">No plans available at the moment.</p>
        @endif
    </div>
</div>
