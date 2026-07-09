<?php

namespace App\Domains\Administration\Policies;

use App\Models\User;

/**
 * Controls access to the admin panel.
 *
 * Only users with the is_admin flag set to true can access admin routes.
 * This is a simple gate check. A full RBAC system with roles and permissions
 * is deferred to a later phase.
 */
class AdminPolicy
{
    /**
     * Determine if the user can access the admin panel.
     */
    public function access(User $user): bool
    {
        return (bool) $user->is_admin;
    }
}
