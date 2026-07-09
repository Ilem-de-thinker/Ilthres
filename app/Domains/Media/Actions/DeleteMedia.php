<?php

namespace App\Domains\Media\Actions;

use App\Domains\Media\Events\MediaDeleted;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;

/**
 * Soft-deletes a media record and removes the physical file from storage.
 *
 * Only the business that owns the media can delete it (enforced by MediaPolicy).
 */
class DeleteMedia
{
    public function __invoke(Media $media): void
    {
        // Delete the physical file from disk.
        Storage::disk($media->disk)->delete($media->path);

        // Delete generated variants if they exist.
        if ($media->variants) {
            foreach ($media->variants as $variant) {
                Storage::disk($media->disk)->delete($variant['path']);
            }
        }

        $media->delete();

        event(new MediaDeleted($media));
    }
}
