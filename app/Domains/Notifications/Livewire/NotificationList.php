<?php

namespace App\Domains\Notifications\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationList extends Component
{
    public ?string $filter = null;

    public function markAsRead(string $id): void
    {
        Notification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', auth()->user()->getMorphClass())
            ->whereNull('read_at')
            ->where('id', $id)
            ->update(['read_at' => now()]);
    }

    public function markAllAsRead(): void
    {
        Notification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', auth()->user()->getMorphClass())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    public function render()
    {
        $user = auth()->user();
        $query = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $user->getMorphClass());

        if ($this->filter === 'unread') {
            $query->whereNull('read_at');
        }

        $notifications = $query->latest()->paginate(20);

        return view('livewire.notifications.notification-list', [
            'notifications' => $notifications,
        ]);
    }
}
