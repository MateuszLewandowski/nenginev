<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class InitializerNotFoundException extends InvalidArgumentException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Initializer %s not found', $name));
    }
}