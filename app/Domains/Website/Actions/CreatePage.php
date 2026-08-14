<?php

namespace App\Domains\Website\Actions;

use App\Domains\Website\Events\PageCreated;
use App\Models\Website;
use App\Models\WebsitePage;

class CreatePage
{
    public function __invoke(Website $website, array $data): WebsitePage
    {
        $maxOrder = $website->pages()->max('sort_order') ?? 0;

        $page = $website->pages()->create([
            'title' => $data['title'],
            'slug' => $data['slug'],
            'sort_order' => $data['sort_order'] ?? $maxOrder + 1,
            'is_active' => $data['is_active'] ?? true,
            'seo' => $data['seo'] ?? null,
        ]);

        event(new PageCreated($page));

        return $page;
    }
}
