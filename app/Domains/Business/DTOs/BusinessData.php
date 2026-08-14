<?php

namespace App\Domains\Business\DTOs;

/**
 * Data transfer object for creating or updating a business profile.
 *
 * Business type is set at creation and cannot be changed later.
 * WhatsApp number is collected during onboarding and stored here.
 */
class BusinessData
{
    public function __construct(
        /** The user who owns this business. */
        public readonly int $userId,

        /** Product store or service business — set once, immutable. */
        public readonly string $type,

        /** Public-facing business name. */
        public readonly string $name,

        /** URL-safe slug derived from the name. */
        public readonly string $slug,

        /** Optional tagline or long description. */
        public readonly ?string $description,

        /** Contact email displayed on the public profile. */
        public readonly ?string $email,

        /** Phone number displayed on the public profile. */
        public readonly ?string $phone,

        /** WhatsApp-enabled number for click-to-chat. */
        public readonly ?string $whatsapp,

        /** Street address. */
        public readonly ?string $address,

        /** City for local search filtering. */
        public readonly ?string $city,

        /** State/region. */
        public readonly ?string $state,

        /** Country (defaults to Nigeria). */
        public readonly ?string $country,

        /** Category ID for classification. */
        public readonly ?int $categoryId,

        /** URL or path to the business logo. */
        public readonly ?string $logo,

        /** URL or path to the cover image. */
        public readonly ?string $cover,

        /** Long-form "About Us" content. */
        public readonly ?string $about = null,

        /** Latitude for map display. */
        public readonly ?float $latitude = null,

        /** Longitude for map display. */
        public readonly ?float $longitude = null,

        /** Whether the business appears in the Explore directory. */
        public readonly bool $isDiscoverable = false,

        /** External website URL (for businesses not hosted on Ilthres). */
        public readonly ?string $websiteUrl = null,
    ) {}
}
