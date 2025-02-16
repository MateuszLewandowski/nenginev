<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

use App\ComputationalIntelligence\Model\Regression\Config;
use App\ComputationalIntelligence\Parameter;
use Symfony\Component\HttpFoundation\Request;

final readonly class ConfigProvider implements ComponentHttpProvider
{

    public static function fromHttpRequest(Request $request): Config
    {
        $config = $request->toArray()['config'];

        return new Config(
            new Parameter($config['batches']),
            new Parameter($config['batchSize']),
            new Parameter($config['alpha']),
            new Parameter($config['epochs']),
            new Parameter($config['minimumChange']),
            new Parameter($config['window']),
            new Parameter($config['holdOut'])
        );

    }
}