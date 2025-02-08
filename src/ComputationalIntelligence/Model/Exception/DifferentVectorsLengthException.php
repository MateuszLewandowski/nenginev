<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class DifferentVectorsLengthException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Different vectors length has been provided.');
    }
}