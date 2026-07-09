<?php

namespace App\Domains\Payment\Events;

use App\Models\Transaction;

class PaymentReceived
{
    public function __construct(
        public Transaction $transaction,
    ) {}
}
