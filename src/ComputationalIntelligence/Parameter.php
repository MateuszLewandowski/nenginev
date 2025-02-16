<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence;

use App\ComputationalIntelligence\Model\Exception\NegativeValueException;
use App\Math\RealNumber;

class Parameter extends RealNumber
{
    private const float MIN = .0;

    public function __construct(float|int|string $value)
    {
        parent::__construct($value);

        if ($this->value < self::MIN) {
            throw new NegativeValueException();
        }
    }
}