<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\DTOs\MarketingLinkData;
use App\Domains\Marketing\Events\LinkCreated;
use App\Models\MarketingLink;

class CreateLink
{
    public function __invoke(MarketingLinkData $data): MarketingLink
    {
        $link = MarketingLink::create([
            'business_id' => $data->businessId,
            'name' => $data->name,
            'slug' => $data->slug,
            'url' => $data->url,
            'description' => $data->description,
            'is_active' => $data->isActive,
        ]);

        event(new LinkCreated($link));

        return $link;
    }
}
