<?php

namespace App\Domains\Theme\Policies;

use App\Models\Theme;
use App\Models\User;

/**
 * Authorizes access to themes.
 *
 * All users can browse and preview themes. Only system-level
 * administration can create, update, or delete themes (deferred).
 */
class ThemePolicy
{
    /** Anyone logged in can browse themes. */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /** Anyone logged in can view a specific theme. */
    public function view(User $user, Theme $theme): bool
    {
        return true;
    }

    /** Theme creation is not available to business owners. */
    public function create(User $user): bool
    {
        return false;
    }

    /** The editing is not available to business owners. */
    public function update(User $user, Theme $theme): bool
    {
        return false;
    }

    /** Theme deletion is not available to business owners. */
    public function delete(User $user, Theme $theme): bool
    {
        return false;
    }
}
