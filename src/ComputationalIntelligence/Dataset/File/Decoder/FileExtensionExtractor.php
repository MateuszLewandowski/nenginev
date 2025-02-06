<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class FileExtensionExtractor
{
    public static function get(UploadedFile $file): string
    {
        return $file->getExtension()
            ?: pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
    }
}
