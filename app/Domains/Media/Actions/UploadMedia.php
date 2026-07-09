<?php

namespace App\Domains\Media\Actions;

use App\Domains\Media\DTOs\MediaData;
use App\Domains\Media\Events\MediaUploaded;
use App\Models\Business;
use App\Models\Media;
use Illuminate\Http\UploadedFile;

/**
 * Uploads a file and creates a Media record for a business.
 *
 * Checks the plan's max_media limit before allowing the upload.
 * The file is stored on the `public` disk under the `uploads/` directory,
 * organised by business ID (e.g. `uploads/1/filename.jpg`).
 */
class UploadMedia
{
    /**
     * Maximum allowed file size in bytes (10MB).
     */
    const MAX_FILE_SIZE = 10_485_760;

    /**
     * Accepted MIME types.
     */
    const ALLOWED_MIMES = [
        'image/jpeg', 'image/png', 'image/webp', 'image/gif',
        'application/pdf',
        'text/csv',
    ];

    public function __invoke(Business $business, UploadedFile $file, ?string $collection = null): Media
    {
        $this->checkPlanLimit($business);
        $this->validateFile($file);

        // Store file in a business-specific directory.
        $path = $file->store("uploads/{$business->id}", 'public');

        $data = new MediaData(
            businessId: $business->id,
            filename: basename($path),
            originalName: $file->getClientOriginalName(),
            mimeType: $file->getMimeType(),
            size: $file->getSize(),
            disk: 'public',
            path: $path,
            collection: $collection,
        );

        $media = Media::create([
            'business_id' => $data->businessId,
            'filename' => $data->filename,
            'original_name' => $data->originalName,
            'mime_type' => $data->mimeType,
            'size' => $data->size,
            'disk' => $data->disk,
            'path' => $data->path,
            'collection' => $data->collection,
        ]);

        event(new MediaUploaded($media));

        return $media;
    }

    /**
     * Validate the uploaded file against size and type constraints.
     */
    private function validateFile(UploadedFile $file): void
    {
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \RuntimeException('File exceeds the maximum allowed size of 10MB.');
        }

        if (! in_array($file->getMimeType(), self::ALLOWED_MIMES, true)) {
            throw new \RuntimeException('File type not supported. Allowed: JPEG, PNG, WebP, GIF, PDF, CSV.');
        }
    }

    /**
     * Check that the business's plan allows more media uploads.
     */
    private function checkPlanLimit(Business $business): void
    {
        $subscription = $business->subscription;

        if (! $subscription || ! $subscription->isActive()) {
            throw new \RuntimeException('Active subscription required to upload media.');
        }

        $planSlug = $subscription->plan->slug;
        $maxMedia = config("plans.{$planSlug}.max_media", 0);

        $currentCount = $business->media()->count();

        if ($currentCount >= $maxMedia) {
            throw new \RuntimeException(
                "Your plan allows a maximum of {$maxMedia} media uploads. Upgrade your plan to upload more."
            );
        }
    }
}
