<?php

namespace App\Domains\Payment\DTOs;

class TransactionData
{
    public function __construct(
        public readonly int $businessId,
        public readonly string $reference,
        public readonly float $amount,
        public readonly string $status = 'pending',
        public readonly ?int $subscriptionId = null,
        public readonly string $currency = 'NGN',
        public readonly ?string $description = null,
        public readonly ?string $gateway = null,
        public readonly ?string $gatewayReference = null,
        public readonly ?array $gatewayData = null,
        public readonly ?string $paidAt = null,
    ) {}
}
