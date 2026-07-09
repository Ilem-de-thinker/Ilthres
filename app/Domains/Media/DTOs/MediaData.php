<?php

namespace App\Domains\Media\DTOs;

/**
 * Data transfer object for creating a media record.
 *
 * Immutable. Used by UploadMedia action to persist file metadata.
 */
class MediaData
{
    public function __construct(
        public readonly int $businessId,
        public readonly string $filename,
        public readonly string $originalName,
        public readonly string $mimeType,
        public readonly int $size,
        public readonly string $disk,
        public readonly string $path,
        public readonly ?string $collection = null,
        public readonly ?array $metadata = null,
    ) {}
}
