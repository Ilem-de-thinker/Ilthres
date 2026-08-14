<?php

namespace App\Domains\Website\Events;

use App\Models\WebsitePage;

class PageCreated
{
    public function __construct(
        public WebsitePage $page,
    ) {}
}
