<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to set up loyalty.</p>
        </div>
        @php return; @endphp
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-lg font-semibold text-gray-900">Loyalty Program</h2>
                <p class="text-sm text-gray-500">{{ $program ? 'Edit your program settings.' : 'Set up a points-based loyalty program.' }}</p>

                <form wire:submit="save" class="mt-6 space-y-6">
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Program Name</label>
                        <input wire:model="name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-medium text-gray-700">Earning Rules</label>
                            <button type="button" wire:click="addRule" class="text-xs font-medium text-gray-600 hover:text-gray-900">+ Add Rule</button>
                        </div>
                        <div class="mt-2 space-y-2">
                            @foreach ($earningRules as $i => $rule)
                                <div class="flex items-center gap-2 rounded-lg border border-gray-200 p-3">
                                    <input wire:model="earningRules.{{ $i }}.label" type="text" placeholder="Label" class="min-w-0 flex-1 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                    <input wire:model="earningRules.{{ $i }}.action" type="text" placeholder="Action key" class="w-24 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                    <input wire:model="earningRules.{{ $i }}.points" type="number" placeholder="Pts" class="w-16 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                    <button type="button" wire:click="removeRule({{ $i }})" class="text-xs text-red-600 hover:text-red-700">x</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-medium text-gray-700">Rewards</label>
                            <button type="button" wire:click="addReward" class="text-xs font-medium text-gray-600 hover:text-gray-900">+ Add Reward</button>
                        </div>
                        <div class="mt-2 space-y-2">
                            @foreach ($rewards as $i => $reward)
                                <div class="flex items-center gap-2 rounded-lg border border-gray-200 p-3">
                                    <input wire:model="rewards.{{ $i }}.label" type="text" placeholder="Reward description" class="min-w-0 flex-1 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                    <input wire:model="rewards.{{ $i }}.points" type="number" placeholder="Points needed" class="w-24 rounded-lg border border-gray-300 px-2 py-1 text-xs">
                                    <button type="button" wire:click="removeReward({{ $i }})" class="text-xs text-red-600 hover:text-red-700">x</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">Save Program</button>
                </form>
            </div>
        </div>

        <div>
            <div class="rounded-xl border border-gray-200 bg-white p-6">
                <h2 class="text-sm font-semibold text-gray-900">Recent Transactions</h2>
                <div class="mt-3 space-y-2">
                    @forelse ($recentTransactions as $txn)
                        <div class="flex items-center justify-between rounded-lg border border-gray-100 p-2">
                            <div>
                                <p class="text-xs font-medium text-gray-900">{{ $txn->description ?? 'Transaction' }}</p>
                                <p class="text-xs text-gray-500">{{ $txn->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-semibold {{ $txn->type === 'earn' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $txn->type === 'earn' ? '+' : '-' }}{{ $txn->points }}
                            </span>
                        </div>
                    @empty
                        <p class="text-center text-xs text-gray-400">No transactions yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>