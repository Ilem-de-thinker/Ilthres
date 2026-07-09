{{--
  Notification bell — shows unread count and recent notifications dropdown.
--}}
<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative rounded-lg p-1.5 text-gray-500 hover:bg-gray-100 hover:text-gray-700">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if ($unreadCount > 0)
            <span class="absolute -right-1 -top-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-500 text-[10px] font-bold text-white">{{ min($unreadCount, 9) }}</span>
        @endif
    </button>

    <div x-show="open" @click.outside="open = false" class="absolute right-0 z-50 mt-2 w-80 rounded-lg border border-gray-200 bg-white shadow-lg" x-cloak>
        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
            <p class="text-sm font-semibold text-gray-900">Notifications</p>
            @if ($unreadCount > 0)
                <button wire:click="markAllAsRead" class="text-xs text-gray-500 hover:text-gray-900">Mark all as read</button>
            @endif
        </div>

        @if ($recent->isEmpty())
            <p class="px-4 py-8 text-center text-sm text-gray-400">No notifications yet.</p>
        @else
            <div class="max-h-80 overflow-y-auto">
                @foreach ($recent as $notification)
                    <div class="flex items-start gap-3 border-b border-gray-50 px-4 py-3 {{ $notification->read_at ? '' : 'bg-blue-50' }}">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $notification->title ?? 'Notification' }}</p>
                            <p class="mt-0.5 text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                        @if (! $notification->read_at)
                            <button wire:click="markAsRead('{{ $notification->id }}')" class="shrink-0 text-xs text-gray-400 hover:text-gray-600">Mark read</button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('dashboard.notifications') }}" class="block border-t border-gray-100 px-4 py-2.5 text-center text-sm text-gray-600 hover:text-gray-900">View all</a>
    </div>
</div>
