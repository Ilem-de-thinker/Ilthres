{{-- Admin user management — search, list, and toggle admin access. --}}
<div>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Users</h1>
        <p class="mt-1 text-sm text-gray-500">Manage registered users and admin access.</p>
    </div>

    <div class="mb-4">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search users..."
            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none">
    </div>

    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Joined</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Admin</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-900">{{ $user->name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $user->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $user->created_at->format('M j, Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if ($user->is_admin)
                                <span class="inline-flex rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-700">Yes</span>
                            @else
                                <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600">No</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="toggleAdmin({{ $user->id }})"
                                class="rounded px-3 py-1 text-xs font-medium {{ $user->is_admin ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200' }}">
                                {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-8 text-center text-sm text-gray-500">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
