<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Exception;

use InvalidArgumentException;

final class CorruptedTimeSeriesException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The time series is corrupted.');
    }
}