<?php

namespace App\Domains\Business\Actions;

use App\Models\Business;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Uploads and assigns a new logo to a business.
 *
 * Stores the file to the `logos` directory on the public disk.
 * The old logo file is deleted to avoid orphaned storage.
 */
class UploadLogo
{
    public function __invoke(Business $business, UploadedFile $file): string
    {
        // Delete the old logo if it exists.
        if ($business->logo) {
            Storage::disk('public')->delete($business->logo);
        }

        $path = $file->store('logos', 'public');

        $business->update(['logo' => $path]);

        return $path;
    }
}
