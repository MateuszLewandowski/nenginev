<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\ActivationFunction\ActivationFunction;
use App\ComputationalIntelligence\Model\ActivationFunction\Linear;
use App\ComputationalIntelligence\Model\ActivationFunction\ReLU;
use App\ComputationalIntelligence\Model\Exception\ActivationFunctionNotFoundException;

final readonly class ActivationFunctionProvider implements ComponentHashmapProvider
{
    public static function fromHashmap(array $hashmap): ActivationFunction
    {
        $activationFunction = $hashmap['sense']['activationFunction'];

        return match($activationFunction) {
            'linear' => new Linear(),
            'relu' => new ReLU(),
            default => throw new ActivationFunctionNotFoundException($activationFunction),
        };
    }
}