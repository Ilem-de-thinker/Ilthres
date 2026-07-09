<?php

namespace App\Domains\Notifications\Actions;

use App\Domains\Notifications\Events\NotificationRead;
use App\Models\Notification;

class MarkAsRead
{
    public function __invoke(Notification $notification): Notification
    {
        $notification->update(['read_at' => now()]);

        event(new NotificationRead($notification));

        return $notification->fresh();
    }
}
