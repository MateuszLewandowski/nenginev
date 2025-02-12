<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\MatrixInitializer\Initializer;
use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\ComputationalIntelligence\Model\Parameter;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;
use App\Math\Tensor\Vector;

final class Dense implements
    Layer,
    FeedForwarding,
    OptimizedBackwardPropagatable,
    Learnable,
    Touchable
{
    private readonly Matrix $input;
    public Matrix $weights;
    public Vector $bias;

    public function __construct(
        private readonly Neurons $neurons,
        private readonly Parameter $alpha,
        private readonly Initializer $initializer,
    ) {
    }

    public function initialize(Neurons $neurons): Neurons
    {
        $this->weights = $this->initializer->initialize($this->neurons, $neurons);
        $this->bias = $this->initializer->initialize($this->neurons, Neurons::single())->asVector();

        return $this->neurons;
    }

    public function feedForward(Matrix $input): Matrix
    {
        $this->input = $input;

        return $this->touch($input);
    }

    public function backPropagation(Optimizer $optimizer, Matrix $gradient, RealNumber $iteration): Gradient
    {
        $weights = $this->weights;
        $weightsDerivative = $gradient->matmul($this->input->transpose())
            ->add($weights->multiply(Scalar::create($this->alpha->value)));

        $this->weights = $this->weights->subtract(
            $optimizer->optimize($this->weights->id(), $weightsDerivative, $iteration)
        );

        $this->bias = $this->bias->subtract(
            $optimizer->optimize($this->bias->id(), $gradient->sum(), $iteration)
        )->vector();

        return new Gradient($this->gradient($weights, $gradient));
    }

    public function touch(Matrix $input): Matrix
    {
        return $this->weights->matmul($input)
            ->add($this->bias);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'neurons' => $this->neurons->length(),
                'alpha' => $this->alpha->value,
                'initializer' => $this->initializer->jsonSerialize(),
            ],
        ];
    }

    public function gradient(Matrix $weights, Matrix $previousGradient): Tensor
    {
        return $weights->transpose()->matmul($previousGradient);
    }
}