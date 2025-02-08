<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

final class DifferentFeaturesAndNeuronsQuantityException extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('The number of features must match the number of neurons.');
    }
}