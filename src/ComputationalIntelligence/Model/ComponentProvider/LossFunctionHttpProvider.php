<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\EvaluationFunction\LossFunction;
use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Exception\EvaluationFunctionNotFoundException;
use Symfony\Component\HttpFoundation\Request;

final readonly class LossFunctionHttpProvider implements ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): LossFunction
    {
        $lossFunction = $request->toArray()['continuous']['lossFunction'];

        return match ($lossFunction) {
            'meanSquaredError' => new MeanSquaredError(),
            default => throw new EvaluationFunctionNotFoundException($lossFunction),
        };
    }
}