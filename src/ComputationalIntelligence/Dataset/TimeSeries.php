<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

use App\Math\Values;
use ArrayIterator;

final class TimeSeries extends ArrayIterator
{
    public function __construct(array $array)
    {
        parent::__construct($array);
    }

    public function extremes(): array
    {
        $min = INF;
        $max = -INF;

        foreach ($this as $value) {
            $min = min($min, $value);
            $max = max($max, $value);
        }

        return [$min, $max];
    }

    public function map(callable $callback): TimeSeries
    {
        return new self(array_map($callback, $this->getArrayCopy()));
    }

    public function values(): array
    {
        return array_values($this->getArrayCopy());
    }

    public function dates(): array
    {
        return array_keys($this->getArrayCopy());
    }
}