<?php

namespace App\Domains\Media\Events;

use App\Models\Media;

/**
 * Fired when a media file is soft-deleted.
 *
 * Listeners may clean up related records, update product/service images,
 * or clear cached page data.
 */
class MediaDeleted
{
    public function __construct(
        public Media $media,
    ) {}
}
