<?php

namespace App\Domains\Business\Actions;

use App\Models\Business;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Uploads and assigns a cover image to a business.
 *
 * Stores the file to the `covers` directory on the public disk.
 * The old cover file is deleted to avoid orphaned storage.
 */
class UploadCover
{
    public function __invoke(Business $business, UploadedFile $file): string
    {
        if ($business->cover) {
            Storage::disk('public')->delete($business->cover);
        }

        $path = $file->store('covers', 'public');

        $business->update(['cover' => $path]);

        return $path;
    }
}
