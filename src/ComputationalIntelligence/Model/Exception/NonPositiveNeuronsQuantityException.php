<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class NonPositiveNeuronsQuantityException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The number of neurons must be a positive integer.');
    }
}