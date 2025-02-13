<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\EvaluationFunction\LossFunction;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final readonly class Continuous implements Layer, BackwardPropagatable
{

    public function __construct(
        public Matrix $input,
        private LossFunction $lossFunction,
    ) {
    }

    public function backPropagation(Scalar $label): Output
    {
        return new Output(
            gradient: $this->lossFunction->differential($this->input, $label)
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