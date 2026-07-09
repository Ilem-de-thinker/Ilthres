<?php

namespace App\Domains\Notifications\Livewire;

use App\Models\Notification;
use Livewire\Component;

class NotificationBell extends Component
{
    public function markAsRead(string $id): void
    {
        $notification = Notification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', auth()->user()->getMorphClass())
            ->findOrFail($id);

        $notification->update(['read_at' => now()]);
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
        $unreadCount = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $user->getMorphClass())
            ->whereNull('read_at')
            ->count();

        $recent = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $user->getMorphClass())
            ->latest()
            ->take(5)
            ->get();

        return view('livewire.notifications.notification-bell', [
            'unreadCount' => $unreadCount,
            'recent' => $recent,
        ]);
    }
}
