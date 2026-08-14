<?php

namespace App\Domains\Website\Actions;

use App\Models\WebsitePage;

class DeletePage
{
    public function __invoke(WebsitePage $page): void
    {
        $page->delete();
    }
}
