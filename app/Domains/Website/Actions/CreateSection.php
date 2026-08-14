<?php

namespace App\Domains\Website\Actions;

use App\Domains\Website\Events\SectionUpdated;
use App\Models\WebsitePage;
use App\Models\WebsiteSection;

class CreateSection
{
    public function __invoke(WebsitePage $page, array $data): WebsiteSection
    {
        $maxOrder = $page->sections()->max('sort_order') ?? 0;

        $section = $page->sections()->create([
            'type' => $data['type'],
            'title' => $data['title'] ?? null,
            'sort_order' => $data['sort_order'] ?? $maxOrder + 1,
            'is_active' => $data['is_active'] ?? true,
            'data' => $data['data'] ?? null,
            'settings' => $data['settings'] ?? null,
        ]);

        event(new SectionUpdated($section));

        return $section;
    }
}
