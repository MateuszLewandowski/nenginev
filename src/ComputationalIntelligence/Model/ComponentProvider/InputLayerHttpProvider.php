<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\Network\Neurons;
use App\ComputationalIntelligence\Model\Network\Stream;
use Symfony\Component\HttpFoundation\Request;

final readonly class InputLayerHttpProvider implements ComponentHttpProvider
{
    public static function fromHttpRequest(Request $request): Stream
    {
        return new Stream(Neurons::create($request->toArray()['stream']['length']));
    }
}