{{--
  Marketing links list — shows all tracking links with click counts and delete action.
--}}
<div>
    @if (! $hasBusiness)
        <div class="rounded-lg border border-dashed border-gray-300 bg-white p-12 text-center">
            <p class="text-sm text-gray-500">Create a business to manage marketing links.</p>
        </div>
    @else
        <div class="rounded-lg border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h3 class="text-sm font-semibold text-gray-900">Marketing Links</h3>
                <a href="{{ route('dashboard.marketing.create') }}" class="rounded-lg bg-gray-900 px-4 py-2 text-sm font-semibold text-white hover:bg-gray-800">New link</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Short link</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($links as $link)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $link->name }}</td>
                                <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                    <a href="{{ route('go', $link) }}" target="_blank" class="text-gray-900 hover:underline">{{ route('go', $link) }}</a>
                                </td>
                                <td class="max-w-xs truncate px-6 py-4 text-sm text-gray-500" title="{{ $link->url }}">{{ $link->url }}</td>
                                <td class="px-6 py-4 text-center text-sm text-gray-900">{{ number_format($link->clicks_count) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' }}">
                                        {{ $link->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button wire:click="delete({{ $link->id }})" wire:confirm="Delete this link?" class="text-sm text-red-600 hover:text-red-800">Delete</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-400">
                                    No marketing links yet.
                                    <a href="{{ route('dashboard.marketing.create') }}" class="text-gray-900 underline">Create one</a>.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
