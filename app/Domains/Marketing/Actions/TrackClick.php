<?php

namespace App\Domains\Marketing\Actions;

use App\Domains\Marketing\Events\LinkClicked;
use App\Models\LinkClick;
use App\Models\MarketingLink;

class TrackClick
{
    public function __invoke(MarketingLink $link): LinkClick
    {
        $click = LinkClick::create([
            'marketing_link_id' => $link->id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'referer' => request()->header('referer'),
            'created_at' => now(),
        ]);

        event(new LinkClicked($link, $click));

        return $click;
    }
}
