<?php

namespace App\Domains\Notifications\Events;

use App\Models\Notification;

class NotificationRead
{
    public function __construct(
        public Notification $notification,
    ) {}
}
