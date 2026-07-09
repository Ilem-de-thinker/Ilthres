<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LinkDeleted;
use App\Models\MarketingLink;

class DeleteLink
{
    public function __invoke(MarketingLink $link): void
    {
        $link->delete();

        event(new LinkDeleted($link));
    }
}
