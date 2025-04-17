<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder\Csv;

use App\ComputationalIntelligence\Dataset\File\Decoder\ContentDecoderArguments;

final readonly class CsvFileContentDecoderArguments implements ContentDecoderArguments
{
    public function __construct(
        public string $separator = ',',
        public string $dateFormat = 'Y-m-d',
        public string $valueFormat = '%.2f',
        public bool $containsHeader = true,
    ) {
    }

    public function intendedFor(): string
    {
        return CsvContentDecoder::class;
    }

    public function jsonSerialize(): array
    {
        return [
            'separator' => $this->separator,
            'dateFormat' => $this->dateFormat,
            'valueFormat' => $this->valueFormat,
            'containsHeader' => $this->containsHeader,
        ];
    }
}