<?php

namespace App\Domains\Business\DTOs;

class LocationData
{
    public function __construct(
        public readonly int $businessId,
        public readonly string $country,
        public readonly string $state,
        public readonly string $city,
        public readonly string $address,
        public readonly ?string $postalCode = null,
        public readonly float $latitude = 0,
        public readonly float $longitude = 0,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
        public readonly ?array $openingHours = null,
        public readonly bool $isPrimary = false,
        public readonly bool $isHeadquarters = false,
    ) {}
}
