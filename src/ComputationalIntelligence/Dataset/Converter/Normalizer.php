<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Converter;

use App\ComputationalIntelligence\Dataset\TimeSeries;

final readonly class Normalizer
{
    private array $extremes;

    public function minMaxFeatureScaling(TimeSeries $timeSeries): TimeSeries
    {
        $this->extremes = $timeSeries->extremes();
        [$min, $max] = $this->extremes;

        return $timeSeries->map(static fn (float $value): float => ($value - $min) / ($max - $min));
    }

    public function minMaxFeatureDescaling(TimeSeries $timeSeries): TimeSeries
    {
        [$min, $max] = $this->extremes;

        return $timeSeries->map(static fn (float $value): float => round($value * ($max - $min) + $min, 2));
    }
}