<div>
    {{-- Page header. --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-1 text-sm text-gray-500">Platform overview and key metrics.</p>
    </div>

    {{-- Stats grid — row 1: core metrics. --}}
    <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm font-medium text-gray-500">Total Revenue</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">₦{{ number_format($totalRevenue) }}</p>
            <p class="mt-1 text-xs {{ $revenueToday > 0 ? 'text-green-600' : 'text-gray-400' }}">
                ₦{{ number_format($revenueToday) }} today &middot; ₦{{ number_format($revenueThisMonth) }} this month
            </p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm font-medium text-gray-500">Total Users</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="mt-1 text-xs {{ $usersToday > 0 ? 'text-green-600' : 'text-gray-400' }}">
                +{{ $usersToday }} today &middot; +{{ $usersThisMonth }} this month
            </p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm font-medium text-gray-500">Businesses</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalBusinesses) }}</p>
            <p class="mt-1 text-xs text-yellow-600">{{ $pendingBusinesses }} pending approval</p>
        </div>
        <div class="rounded-lg border border-gray-200 bg-white p-4">
            <p class="text-sm font-medium text-gray-500">Active Subscriptions</p>
            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($activeSubscriptions) }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $trialSubscriptions }} in trial</p>
        </div>
    </div>

    {{-- Charts row. --}}
    <div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="rounded-lg border border-gray-200 bg-white p-4" x-data="chart($el)" data-chart='@json($revenueConfig)'>
            <h3 class="mb-1 text-sm font-semibold text-gray-900">Revenue (30 days)</h3>
            <p class="mb-4 text-xs text-gray-500">Daily revenue in NGN</p>
            <div style="height: 160px;"><canvas></canvas></div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4" x-data="chart($el)" data-chart='@json($userConfig)'>
            <h3 class="mb-1 text-sm font-semibold text-gray-900">User Signups (30 days)</h3>
            <p class="mb-4 text-xs text-gray-500">Daily new registrations</p>
            <div style="height: 160px;"><canvas></canvas></div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-4" x-data="chart($el)" data-chart='@json($subscriptionConfig)'>
            <h3 class="mb-1 text-sm font-semibold text-gray-900">Subscription Breakdown</h3>
            <p class="mb-4 text-xs text-gray-500">Current status distribution</p>
            <div style="height: 160px;"><canvas></canvas></div>
        </div>
    </div>

    {{-- Tables row. --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Recent transactions. --}}
        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-900">Recent Payments</h3>
                <a href="{{ route('admin.payments') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($recentTransactions as $txn)
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $txn->description ?: $txn->reference }}</p>
                            <p class="text-xs text-gray-500">{{ $txn->business?->name ?? '—' }} &middot; {{ $txn->paid_at?->diffForHumans() ?? $txn->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="ml-4 text-right">
                            <p class="text-sm font-semibold text-gray-900">₦{{ number_format($txn->amount) }}</p>
                            <span class="inline-flex rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-700">{{ $txn->status }}</span>
                        </div>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-gray-500">No payments yet.</p>
                @endforelse
            </div>
        </div>

        {{-- Recent subscriptions. --}}
        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-4 py-3">
                <h3 class="text-sm font-semibold text-gray-900">Recent Subscriptions</h3>
                <a href="{{ route('admin.subscriptions') }}" class="text-xs font-medium text-indigo-600 hover:text-indigo-500">View all</a>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse ($recentSubscriptions as $sub)
                    <div class="flex items-center justify-between px-4 py-3">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $sub->business?->name ?? '—' }}</p>
                            <p class="text-xs text-gray-500">{{ $sub->plan?->name ?? 'No plan' }} &middot; {{ $sub->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : ($sub->status === 'trial' ? 'bg-blue-100 text-blue-700' : ($sub->status === 'expired' ? 'bg-red-100 text-red-700' : 'bg-gray-100 text-gray-700')) }}">
                            {{ ucfirst($sub->status) }}
                        </span>
                    </div>
                @empty
                    <p class="px-4 py-6 text-center text-sm text-gray-500">No subscriptions yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent users. --}}
    <div class="mt-6 rounded-lg border border-gray-200 bg-white">
        <div class="border-b border-gray-200 px-4 py-3">
            <h3 class="text-sm font-semibold text-gray-900">Recent Users</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse ($recentUsers as $user)
                <div class="flex items-center justify-between px-4 py-3">
                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="px-4 py-6 text-center text-sm text-gray-500">No users yet.</p>
            @endforelse
        </div>
    </div>
</div>
