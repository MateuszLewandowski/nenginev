<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

use App\ComputationalIntelligence\Dataset\File\Decoder\Exception\ContentDecodingException;
use App\ComputationalIntelligence\Dataset\TimeSeries;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

abstract readonly class ContentDecoderStrategy
{
    private const string DATE_FORMAT = 'Y-m-d H:i';

    public function __construct(
        protected ContentDecoderArguments $arguments,
    ) {
    }

    abstract public function decode(UploadedFile $file): TimeSeries;

    protected function handleDecoding(callable $fn): TimeSeries
    {
        try {
            return $fn();
        } catch (Throwable $e) {
            throw new ContentDecodingException($e);
        }
    }

    protected function parseDateTime(string $date): ?string
    {
        return DateTimeImmutable::createFromFormat($this->arguments->dateFormat, $date)
            ->format(self::DATE_FORMAT);
    }

    protected function getCurrentValue(TimeSeries $result, string $key): float
    {
        return $result->offsetExists($key) ? $result->offsetGet($key) : .0;
    }
}