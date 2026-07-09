<?php

namespace App\Domains\Media\Actions;

use App\Models\Media;

/**
 * Updates a media record's collection and/or metadata.
 *
 * Used to organise files into logical groups (e.g. "gallery", "documents")
 * and attach custom metadata for searchability.
 */
class OrganizeMedia
{
    public function __invoke(Media $media, ?string $collection = null, ?array $metadata = null): Media
    {
        $updates = [];

        if ($collection !== null) {
            $updates['collection'] = $collection;
        }

        if ($metadata !== null) {
            $updates['metadata'] = $metadata;
        }

        if (! empty($updates)) {
            $media->update($updates);
        }

        return $media;
    }
}
