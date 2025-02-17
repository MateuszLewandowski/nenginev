<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Measurement;

enum TimeUnit: int
{
    case MILI_SECONDS = 1000000;
    case SECONDS = 1000000000;

    public function getName(): string
    {
        return match ($this) {
            self::MILI_SECONDS => 'ms',
            self::SECONDS => 's',
        };
    }

    public function cast(float $value): float
    {
        return $value / $this->value;
    }
}