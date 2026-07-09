{{--
  Full notification list page.
--}}
<div>
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Notifications</h1>
        <div class="flex items-center gap-3">
            <select wire:model.live="filter" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm">
                <option value="">All</option>
                <option value="unread">Unread</option>
            </select>
            <button wire:click="markAllAsRead" class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm text-gray-700 hover:bg-gray-50">Mark all as read</button>
        </div>
    </div>

    @if ($notifications->isEmpty())
        <div class="rounded-xl border border-gray-200 bg-white px-6 py-12 text-center">
            <p class="text-gray-400">No notifications found.</p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white">
            <div class="divide-y divide-gray-100">
                @foreach ($notifications as $notification)
                    <div class="flex items-start gap-4 px-6 py-4 {{ $notification->read_at ? '' : 'bg-blue-50/50' }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title ?? 'Notification' }}</p>
                            @if ($notification->url)
                                <a href="{{ $notification->url }}" class="mt-0.5 text-sm text-gray-600 hover:text-gray-900 line-clamp-2">{{ $notification->data['body'] ?? '' }}</a>
                            @else
                                <p class="mt-0.5 text-sm text-gray-600 line-clamp-2">{{ $notification->data['body'] ?? '' }}</p>
                            @endif
                            <p class="mt-1 text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (! $notification->read_at)
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="shrink-0 rounded px-2 py-1 text-xs text-gray-500 hover:bg-gray-100">Mark read</button>
                        @else
                            <span class="shrink-0 rounded px-2 py-1 text-xs text-gray-400">Read</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
