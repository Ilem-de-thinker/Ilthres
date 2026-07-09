<?php

namespace App\Domains\Payment\Events;

use App\Models\Transaction;

class PaymentFailed
{
    public function __construct(
        public Transaction $transaction,
    ) {}
}
