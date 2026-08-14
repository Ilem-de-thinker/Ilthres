<?php

namespace App\Domains\Website\Actions;

use App\Models\WebsitePage;

class UpdatePage
{
    public function __invoke(WebsitePage $page, array $data): WebsitePage
    {
        $page->update($data);

        return $page->fresh();
    }
}
