<?php

namespace App\Domains\Website\Actions;

use App\Models\WebsitePage;

class ReorderSections
{
    public function __invoke(WebsitePage $page, array $orderedIds): void
    {
        foreach ($orderedIds as $position => $sectionId) {
            $page->sections()->where('id', $sectionId)->update(['sort_order' => $position + 1]);
        }
    }
}
