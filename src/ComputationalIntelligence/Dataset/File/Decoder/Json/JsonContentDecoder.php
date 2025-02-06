<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder\Json;

use App\ComputationalIntelligence\Dataset\File\Decoder\ContentDecoderStrategy;
use App\Math\RealNumber;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\ComputationalIntelligence\Dataset\TimeSeries;

/** @property JsonContentDecoderArguments $arguments */
final readonly class JsonContentDecoder extends ContentDecoderStrategy
{
    public function __construct(
        array $arguments,
    ) {
        parent::__construct(
            new JsonContentDecoderArguments(
                $arguments['dateFormat'],
                $arguments['valueFormat'],
            )
        );
    }

    public function decode(UploadedFile $file): TimeSeries
    {
        return $this->handleDecoding(
            function () use ($file): TimeSeries {
                $result = new TimeSeries();
                $payload = json_decode($file->getContent(), true, 512, JSON_THROW_ON_ERROR);

                foreach ($payload as $key => $value) {
                    $key = $this->parseDateTime($key);

                    if (!$key) {
                        continue;
                    }

                    $currentValue = $this->getCurrentValue($result, $key);
                    $value = (float) sprintf($this->arguments->valueFormat, $value);

                    $result->offsetSet($key, round($currentValue + $value, RealNumber::PRECISION));
                }

                return $result;
            }
        );
    }
}