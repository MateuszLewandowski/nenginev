<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\EvaluationFunction;

use App\ComputationalIntelligence\Model\ComponentProvider\ComponentHttpProvider;
use App\ComputationalIntelligence\Model\Exception\OptimizerNotFoundException;
use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use Symfony\Component\HttpFoundation\Request;

final readonly class OptimizerHttpProvider implements ComponentHttpProvider
{
    private const string KEY = 'optimizer';

    public static function fromHttpRequest(Request $request): Optimizer
    {
        $optimizer = $request->toArray()['optimizer'];

        return match ($optimizer) {
            'adam' => new Adam(),
            default => throw new OptimizerNotFoundException($optimizer),
        };
    }
}