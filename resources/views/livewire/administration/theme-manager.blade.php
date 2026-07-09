{{-- Admin theme management — enable/disable themes. --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Themes</h1>
        <p class="mt-1 text-sm text-gray-500">Enable or disable themes across the platform.</p>
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Slug</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Premium</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Active</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($themes as $theme)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $theme->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $theme->slug }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($theme->is_premium)
                                <span class="inline-flex rounded-full bg-yellow-100 px-2.5 py-0.5 text-xs font-medium text-yellow-700">Premium</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if ($theme->is_active)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Yes</span>
                            @else
                                <span class="inline-flex rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-700">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleActive({{ $theme->id }})"
                                class="rounded px-3 py-1 text-xs font-medium border {{ $theme->is_active ? 'border-red-200 bg-red-50 text-red-700 hover:bg-red-100' : 'border-green-200 bg-green-50 text-green-700 hover:bg-green-100' }}">
                                {{ $theme->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No themes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $themes->links() }}
    </div>
</div>
