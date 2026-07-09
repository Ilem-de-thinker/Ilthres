<?php

namespace App\Domains\Media\Policies;

use App\Models\Media;
use App\Models\User;

/**
 * Authorizes access to media resources.
 *
 * Users can only view, update, or delete media that belongs to
 * a business they own. Guest users cannot access media at all.
 */
class MediaPolicy
{
    /** Only the business owner can view media files. */
    public function view(User $user, Media $media): bool
    {
        return $user->id === $media->business->user_id;
    }

    /** Only the business owner can create uploads. */
    public function create(User $user): bool
    {
        return true; // Ownership is enforced in the action by passing the business.
    }

    /** Only the business owner can update media metadata. */
    public function update(User $user, Media $media): bool
    {
        return $user->id === $media->business->user_id;
    }

    /** Only the business owner can delete media. */
    public function delete(User $user, Media $media): bool
    {
        return $user->id === $media->business->user_id;
    }
}
