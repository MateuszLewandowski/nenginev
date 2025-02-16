<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Initializer\Initializer;
use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\ComputationalIntelligence\Parameter;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;
use Symfony\Component\Uid\Uuid;

final class Dense implements
    Layer,
    FeedForwarding,
    OptimizedBackwardPropagatable,
    Touchable
{
    private Uuid $weightsId;
    private Uuid $biasId;
    private Matrix $input;
    public Matrix $weights;
    public Vector $bias;

    public function __construct(
        public readonly Neurons $neurons,
        private readonly Parameter $alpha,
        private readonly Initializer $initializer,
    ) {
    }

    public function initialize(Neurons $neurons): Neurons
    {
        $this->weights = $this->initializer->initialize($this->neurons, $neurons);
        $this->weightsId = $this->weights->id();
        $this->bias = $this->initializer->initialize($this->neurons, Neurons::single())->asVector();
        $this->biasId = $this->bias->id();

        return $this->neurons;
    }

    public function feedForward(Matrix $input): Matrix
    {
        $this->input = $input;

        return $this->touch($input);
    }

    public function backPropagation(Optimizer $optimizer, Matrix $gradient, RealNumber $epoch): Matrix
    {
        $weights = $this->weights;
        $gradient = $gradient->matmul($this->input->transpose());

        $weightsDerivative = $gradient->add($weights->multiply(Scalar::create($this->alpha->value)));

        $this->weights = $this->weights->subtract(
            $optimizer->optimize($this->weightsId, $weightsDerivative, $epoch)
        );

        $this->bias = $this->bias->subtract(
            $optimizer->optimize($this->biasId, $gradient->sum(), $epoch)
        )->vector();

        return $weights->transpose()->matmul($gradient);
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
                'neurons' => $this->neurons->value,
                'alpha' => $this->alpha->value,
                'initializer' => $this->initializer->jsonSerialize(),
            ],
        ];
    }
}