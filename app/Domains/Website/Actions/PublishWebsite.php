<?php

namespace App\Domains\Website\Actions;

use App\Domains\Website\Events\WebsitePublished;
use App\Models\Website;

class PublishWebsite
{
    public function __invoke(Website $website): Website
    {
        $website->update([
            'is_active' => true,
        ]);

        event(new WebsitePublished($website));

        return $website;
    }
}
