<?php

namespace App\Domains\Payment\Policies;

use App\Models\Transaction;
use App\Models\User;

class TransactionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->businesses()->exists();
    }

    public function view(User $user, Transaction $transaction): bool
    {
        return $user->id === $transaction->business->user_id;
    }
}
