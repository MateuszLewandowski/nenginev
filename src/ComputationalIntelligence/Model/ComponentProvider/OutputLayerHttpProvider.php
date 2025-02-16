<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\Network\Continuous;
use Symfony\Component\HttpFoundation\Request;

final readonly class OutputLayerHttpProvider implements ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): Continuous
    {
        return new Continuous(LossFunctionHttpProvider::fromHttpRequest($request));
    }
}