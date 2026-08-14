<?php

namespace App\Domains\Website\DTOs;

/**
 * Data transfer object for creating or updating a website.
 */
class WebsiteData
{
    public function __construct(
        /** The business that owns this website. */
        public readonly int $businessId,

        /** Unique subdomain slug (e.g., "my-shop"). */
        public readonly string $subdomain,

        /** Optional custom domain (e.g., "www.mysite.com"). */
        public readonly ?string $customDomain = null,

        /** Theme to apply to this website. */
        public readonly ?int $themeId = null,

        /** Whether the website is publicly accessible. */
        public readonly bool $isActive = false,

        /** Whether SSL is enabled for this website. */
        public readonly bool $sslEnabled = false,

        /** DNS configuration records. */
        public readonly ?array $dnsRecords = null,

        /** SEO metadata. */
        public readonly ?array $seo = null,
    ) {}
}
