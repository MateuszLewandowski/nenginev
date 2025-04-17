<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Converter;

use App\ComputationalIntelligence\Dataset\Converter\Exception\TimeSeriesLengthException;
use App\ComputationalIntelligence\Dataset\TimeSeries;
use App\Math\RealNumber;
use App\Math\Values;

final readonly class Splitter
{
    /**
     * @return Values[]
     * @example [samples, labels]
     */
    public function split(TimeSeries $timeSeries, RealNumber $batches, RealNumber $batchSize): array
    {
        $samples = $labels = [];
        $length = $batches->asInteger();
        $size = $batchSize->asInteger();
        $values = $timeSeries->values();

        $requiredLength = $length + $size;
        if ($timeSeries->count() < $requiredLength) {
            throw new TimeSeriesLengthException();
        }

        for ($i = 0; $i < $length; ++$i) {
            for ($j = 0; $j < $size; ++$j) {
                $samples[$i][$j] = $values[$i + $j];
            }
            $labels[$i] = $values[$i + $size];
        }

        return [
            Values::create($samples), Values::create($labels)
        ];
    }
}