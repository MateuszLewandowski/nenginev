<?php

declare(strict_types=1);

namespace App\Dataset;

final readonly class TimeSeries
{
    public function __construct(
        public array $data = [],
    ) {
    }
}