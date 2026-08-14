<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\PromoBannerCreated;
use App\Models\PromoBanner;

class CreatePromoBanner
{
    public function __invoke(array $data): PromoBanner
    {
        $banner = PromoBanner::create($data);

        event(new PromoBannerCreated($banner));

        return $banner;
    }
}
