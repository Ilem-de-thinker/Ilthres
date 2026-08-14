<?php

namespace App\Domains\Website\Policies;

use App\Models\User;
use App\Models\Website;

/**
 * Authorizes access to website resources.
 */
class WebsitePolicy
{
    /** Only the website owner can manage it. */
    public function update(User $user, Website $website): bool
    {
        return $user->id === $website->business->user_id;
    }

    /** Only the website owner can delete it. */
    public function delete(User $user, Website $website): bool
    {
        return $user->id === $website->business->user_id;
    }
}
