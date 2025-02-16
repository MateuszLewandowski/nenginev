<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use RuntimeException;

final class ActivationFunctionNotFoundException extends RuntimeException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Activation function %s not found', $name));
    }
}