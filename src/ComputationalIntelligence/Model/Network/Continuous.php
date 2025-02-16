<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\EvaluationFunction\LossFunction;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final  class Continuous implements
    Layer,
    FeedForwarding,
    BackwardPropagatable
{
    private Matrix $input;

    public function __construct(
        private readonly LossFunction $lossFunction,
    ) {
    }

    public function feedForward(Matrix $input): Matrix
    {
        $this->input = $input;

        return $input;
    }

    public function backPropagation(Scalar $label): Output
    {
        return new Output(
            gradient: $this->lossFunction->derivative($this->input, $label)
                ->divide(Scalar::create($this->input->columns()))
                ->matrix(),
            loss: $this->lossFunction->differential($this->input, $label)
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'neurons' => 1,
                'lossFunction' => $this->lossFunction->jsonSerialize(),
            ],
        ];
    }
}