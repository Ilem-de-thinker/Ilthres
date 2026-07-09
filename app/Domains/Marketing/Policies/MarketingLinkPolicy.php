<?php

namespace App\Domains\Marketing\Policies;

use App\Models\MarketingLink;
use App\Models\User;

class MarketingLinkPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function view(User $user, MarketingLink $link): bool
    {
        return $user->id === $link->business->user_id;
    }

    public function create(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function update(User $user, MarketingLink $link): bool
    {
        return $user->id === $link->business->user_id;
    }

    public function delete(User $user, MarketingLink $link): bool
    {
        return $user->id === $link->business->user_id;
    }
}
