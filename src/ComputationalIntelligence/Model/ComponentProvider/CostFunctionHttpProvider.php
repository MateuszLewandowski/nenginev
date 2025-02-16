<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\EvaluationFunction\CostFunction;
use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Exception\EvaluationFunctionNotFoundException;
use Symfony\Component\HttpFoundation\Request;

final readonly class CostFunctionHttpProvider implements ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): CostFunction
    {
        $costFunction = $request->toArray()['costFunction'];

        return match ($costFunction) {
            'meanSquaredError' => new MeanSquaredError(),
            default => throw new EvaluationFunctionNotFoundException($costFunction),
        };
    }
}