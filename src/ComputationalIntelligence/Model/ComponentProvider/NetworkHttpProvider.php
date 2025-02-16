<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\EvaluationFunction\OptimizerHttpProvider;
use App\ComputationalIntelligence\Network;
use Symfony\Component\HttpFoundation\Request;

final readonly class NetworkHttpProvider implements ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): Network
    {
        return new Network(
            InputLayerHttpProvider::fromHttpRequest($request),
            OutputLayerHttpProvider::fromHttpRequest($request),
            OptimizerHttpProvider::fromHttpRequest($request),
            ...HiddenLayerHttpProvider::fromHttpRequest($request),
        );
    }
}