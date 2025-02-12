<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class DropoutCoefficientOutOfRangeException extends InvalidArgumentException
{
    public function __construct(float $min, float $max)
    {
        parent::__construct(sprintf('Given dropout coefficient is out of range (%s; %s)', $min, $max));
    }
}