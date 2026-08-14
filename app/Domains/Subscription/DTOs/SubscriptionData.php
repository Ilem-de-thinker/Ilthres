<?php

namespace App\Domains\Subscription\DTOs;

class SubscriptionData
{
    public function __construct(
        public readonly int $businessId,
        public readonly int $planId,
        public readonly string $status = 'trial',
        public readonly ?float $pricePaid = null,
        public readonly ?string $gateway = null,
        public readonly ?string $gatewayReference = null,
        public readonly ?array $gatewayData = null,
        public readonly ?string $trialEndsAt = null,
        public readonly ?string $startsAt = null,
        public readonly ?string $endsAt = null,
    ) {}
}
