<?php

namespace App\Domains\Marketing\Actions;

use App\Models\PromoBanner;

class UpdatePromoBanner
{
    public function __invoke(PromoBanner $banner, array $data): PromoBanner
    {
        $banner->update($data);

        return $banner;
    }
}
