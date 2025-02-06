<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

use App\ComputationalIntelligence\Dataset\TimeSeries;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final readonly class FileContentDecoder
{
    public function __construct(
        private ContentDecoderStrategy $decoder,
    ) {
    }

    public function decode(UploadedFile $file): TimeSeries
    {
        return $this->decoder->decode($file);
    }
}