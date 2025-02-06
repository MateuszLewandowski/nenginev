<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Converter;

use App\ComputationalIntelligence\Dataset\TimeSeries;

final readonly class Normalizer
{
    public function __construct(
        private TimeSeries $timeSeries,
    ) {
    }

    public function minMaxFeatureScaling(): TimeSeries
    {
        [$min, $max] = $this->timeSeries->extremes();

        return $this->timeSeries->map(static fn (float $value): float => ($value - $min) / ($max - $min));
    }

    public function minMaxFeatureDescaling(TimeSeries $timeSeries): TimeSeries
    {
        [$min, $max] = $this->timeSeries->extremes();

        return $timeSeries->map(static fn (float $value): float => round($value * ($max - $min) + $min, 2));
    }
}