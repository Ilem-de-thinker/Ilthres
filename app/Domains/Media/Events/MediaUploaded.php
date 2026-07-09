<?php

namespace App\Domains\Media\Events;

use App\Models\Media;

/**
 * Fired when a new media file is uploaded for a business.
 *
 * Listeners may generate image thumbnails, run virus scans,
 * update search indexes, or trigger webhooks.
 */
class MediaUploaded
{
    public function __construct(
        public Media $media,
    ) {}
}
