<?php

namespace App\Domains\Notifications\Events;

use App\Models\User;

class NotificationSent
{
    public function __construct(
        public User $user,
        public string $type,
        public array $data,
    ) {}
}
