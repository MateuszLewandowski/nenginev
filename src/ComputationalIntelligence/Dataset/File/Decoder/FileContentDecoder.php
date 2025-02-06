<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class FileContentDecoder
{
    public function __construct(
        private ContentDecoderStrategy $decoder,
    ) {
    }

    public function decode(UploadedFile $file): \ArrayIterator
    {
        return $this->decoder->decode($file);
    }
}