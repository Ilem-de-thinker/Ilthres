{{-- Admin business management — search, filter, activate/suspend/delete, change plan. --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Businesses</h1>
        <p class="mt-1 text-sm text-gray-500">Search, filter, and manage all businesses on the platform.</p>
    </div>

    <div class="mb-4 flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search businesses..."
            class="flex-1 min-w-[200px] rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none">
        <select wire:model.live="filterStatus"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Status</option>
            <option value="active">Active</option>
            <option value="suspended">Suspended</option>
            <option value="deleted">Deleted</option>
        </select>
        <select wire:model.live="filterType"
            class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none">
            <option value="">All Types</option>
            <option value="linked">Linked</option>
            <option value="hosted">Hosted</option>
        </select>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Owner</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Plan</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($businesses as $business)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">
                            {{ $business->name }}
                            @if ($business->website_url)
                                <span class="ml-1.5 inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-[10px] font-medium text-blue-700">Linked</span>
                            @else
                                <span class="ml-1.5 inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-[10px] font-medium text-purple-700">Hosted</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $business->user?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">
                            {{ $business->isProductStore() ? 'Store' : 'Service' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($business->trashed())
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">Deleted</span>
                            @elseif ($business->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Active</span>
                            @else
                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700">Suspended</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">
                            @if ($changingPlanFor === $business->id)
                                <div class="flex items-center justify-center gap-2">
                                    <select wire:model="selectedPlanId"
                                        class="rounded border border-gray-300 bg-white px-2 py-1 text-xs text-gray-900 focus:border-indigo-500 focus:outline-none">
                                        @foreach ($plans as $plan)
                                            <option value="{{ $plan->id }}">{{ $plan->name }} (₦{{ number_format($plan->price) }})</option>
                                        @endforeach
                                    </select>
                                    <button wire:click="changePlan({{ $business->id }})"
                                        class="rounded bg-indigo-600 px-2 py-1 text-xs font-medium text-white hover:bg-indigo-700">
                                        Save
                                    </button>
                                    <button wire:click="cancelPlanChange"
                                        class="text-xs text-gray-500 hover:text-gray-700">
                                        Cancel
                                    </button>
                                </div>
                            @else
                                <div class="flex items-center justify-center gap-2">
                                    <span>{{ $business->subscription?->plan?->name ?? '—' }}</span>
                                    <button wire:click="startPlanChange({{ $business->id }})"
                                        class="text-xs text-indigo-600 hover:text-indigo-500">
                                        Change
                                    </button>
                                </div>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                @if (! $business->trashed())
                                    <button wire:click="toggleActive({{ $business->id }})"
                                        class="rounded px-3 py-1 text-xs font-medium border {{ $business->is_active ? 'border-yellow-200 bg-yellow-50 text-yellow-700 hover:bg-yellow-100' : 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100' }}">
                                        {{ $business->is_active ? 'Suspend' : 'Activate' }}
                                    </button>
                                @endif
                                <button wire:click="deleteBusiness({{ $business->id }})"
                                    wire:confirm="Permanently delete {{ $business->name }}? This cannot be undone."
                                    class="rounded border border-red-200 bg-red-50 px-3 py-1 text-xs font-medium text-red-700 hover:bg-red-100">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">No businesses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $businesses->links() }}
    </div>
</div>
