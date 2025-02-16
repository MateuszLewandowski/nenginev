<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Application;

use App\ComputationalIntelligence\Model\ComponentProvider\ConfigProvider;
use App\ComputationalIntelligence\Model\ComponentProvider\CostFunctionHttpProvider;
use App\ComputationalIntelligence\Model\ComponentProvider\NetworkHttpProvider;
use App\ComputationalIntelligence\Model\EvaluationFunction\CostFunction;
use App\ComputationalIntelligence\Model\Regression\Config;
use App\ComputationalIntelligence\Network;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Request;

final readonly class TrainModelRequest implements JsonSerializable
{
    public function __construct(
        public Config $config,
        public Network $network,
        public CostFunction $costFunction,
    ) {
    }

    public static function fromHttpRequest(Request $request): self
    {
        return new self(
            ConfigProvider::fromHttpRequest($request),
            NetworkHttpProvider::fromHttpRequest($request),
            CostFunctionHttpProvider::fromHttpRequest($request),
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'config' => $this->config->jsonSerialize(),
                'network' => $this->network->jsonSerialize(),
                'costFunction' => $this->costFunction->jsonSerialize(),
            ],
        ];
    }
}
