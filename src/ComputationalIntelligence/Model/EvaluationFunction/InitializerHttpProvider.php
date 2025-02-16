<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\EvaluationFunction;

use App\ComputationalIntelligence\Model\ComponentProvider\ComponentHashmapProvider;
use App\ComputationalIntelligence\Model\Exception\InitializerNotFoundException;
use App\ComputationalIntelligence\Model\Initializer\He;
use App\ComputationalIntelligence\Model\Initializer\Initializer;

final readonly class InitializerHttpProvider implements ComponentHashmapProvider
{
    public static function fromHashmap(array $hashmap): Initializer
    {
        $initializer = $hashmap['dense']['initializer'];

        return match ($initializer) {
            'he' => new He(),
            default => throw new InitializerNotFoundException($initializer),
        };
    }
}