<?php

namespace App\Domains\Website\Actions;

use App\Domains\Website\DTOs\WebsiteData;
use App\Domains\Website\Events\WebsiteCreated;
use App\Models\Website;
use Illuminate\Support\Str;

class CreateWebsite
{
    public function __invoke(WebsiteData $data): Website
    {
        $subdomain = Str::slug($data->subdomain);
        $subdomain = $this->ensureUniqueSubdomain($subdomain);

        $website = Website::create([
            'business_id' => $data->businessId,
            'theme_id' => $data->themeId,
            'subdomain' => $subdomain,
            'custom_domain' => $data->customDomain,
            'is_active' => $data->isActive,
            'ssl_enabled' => $data->sslEnabled,
            'dns_records' => $data->dnsRecords,
            'seo' => $data->seo,
        ]);

        event(new WebsiteCreated($website));

        return $website;
    }

    private function ensureUniqueSubdomain(string $subdomain): string
    {
        $original = $subdomain;
        $suffix = 1;

        while (Website::where('subdomain', $subdomain)->exists()) {
            $subdomain = $original.'-'.$suffix++;
        }

        return $subdomain;
    }
}
