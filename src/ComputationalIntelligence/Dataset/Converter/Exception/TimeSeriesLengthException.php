<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Converter\Exception;

use InvalidArgumentException;

final class TimeSeriesLengthException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The TimeSeries does not have enough data for the given batches and batch size.');
    }
}