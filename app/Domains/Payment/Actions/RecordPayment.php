<?php

namespace App\Domains\Payment\Actions;

use App\Domains\Payment\DTOs\TransactionData;
use App\Domains\Payment\Events\PaymentFailed;
use App\Domains\Payment\Events\PaymentReceived;
use App\Models\Transaction;

class RecordPayment
{
    public function __invoke(TransactionData $data): Transaction
    {
        $transaction = Transaction::create([
            'business_id' => $data->businessId,
            'subscription_id' => $data->subscriptionId,
            'reference' => $data->reference,
            'amount' => $data->amount,
            'currency' => $data->currency,
            'status' => $data->status,
            'description' => $data->description,
            'gateway' => $data->gateway,
            'gateway_reference' => $data->gatewayReference,
            'gateway_data' => $data->gatewayData,
            'paid_at' => $data->paidAt,
        ]);

        if ($data->status === 'failed') {
            event(new PaymentFailed($transaction));
        } else {
            event(new PaymentReceived($transaction));
        }

        return $transaction;
    }
}
