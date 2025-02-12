<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;


use App\ComputationalIntelligence\Model\EvaluationFunction\LossFunction;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;

final readonly class Continuous implements Layer, BackwardPropagatable, Trainable
{

    public function __construct(
        public Matrix $input,
        private LossFunction $lossFunction,
    ) {
    }


    public function backPropagation(Scalar $label): Output
    {
        return new Output(
            gradient: $this->gradient($this->input, $label),
            loss: $this->lossFunction->differential($this->input, $label)
        );
    }

    public function gradient(Matrix $input, Scalar $expected): Tensor
    {
        return $this->lossFunction->differential($input, $expected)
            ->divide(Scalar::create($input->columns()));
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