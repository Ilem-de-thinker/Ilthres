<?php

namespace App\Domains\Notifications\Actions;

use App\Domains\Notifications\Events\NotificationRead;
use App\Models\Notification;
use App\Models\User;

class MarkAllAsRead
{
    public function __invoke(User $user): void
    {
        $notifications = Notification::where('notifiable_id', $user->id)
            ->where('notifiable_type', $user->getMorphClass())
            ->whereNull('read_at')
            ->get();

        foreach ($notifications as $notification) {
            $notification->update(['read_at' => now()]);
            event(new NotificationRead($notification));
        }
    }
}
