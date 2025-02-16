<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class EvaluationFunctionNotFoundException extends InvalidArgumentException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Evaluation function %s not found', $name));
    }
}