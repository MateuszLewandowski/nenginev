<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder\Json;

use App\ComputationalIntelligence\Dataset\File\Decoder\ContentDecoderArguments;

final readonly class JsonContentDecoderArguments implements ContentDecoderArguments
{
    public function __construct(
        public string $dateFormat = 'Y-m-d H:i:s',
        public string $valueFormat = '%.2f',
    ) {
    }

    public function intendedFor(): string
    {
        return JsonContentDecoder::class;
    }
}