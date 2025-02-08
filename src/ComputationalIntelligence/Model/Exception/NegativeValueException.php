<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class NegativeValueException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Value must be in the range [0, ∞)');
    }
}