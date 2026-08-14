<?php

namespace App\Domains\Website\Actions;

use App\Models\Website;

class ReorderPages
{
    public function __invoke(Website $website, array $orderedIds): void
    {
        foreach ($orderedIds as $position => $pageId) {
            $website->pages()->where('id', $pageId)->update(['sort_order' => $position + 1]);
        }
    }
}
