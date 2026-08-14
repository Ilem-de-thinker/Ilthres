<?php

namespace App\Domains\Website\Actions;

use App\Domains\Website\Events\SectionUpdated;
use App\Models\WebsiteSection;

class UpdateSection
{
    public function __invoke(WebsiteSection $section, array $data): WebsiteSection
    {
        $section->update($data);

        event(new SectionUpdated($section));

        return $section->fresh();
    }
}
