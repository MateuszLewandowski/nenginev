<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;

final readonly class Hidden implements
    Layer,
    FeedForwarding,
    OptimizedBackwardPropagatable,
    Touchable
{
    public function __construct(
        private Dense $dense,
        private Sense $sense,
        private Dropout $dropout,
    ) {
    }

    public function initialize(Neurons $input, Optimizer $optimizer): Neurons
    {
        $this->dense->initialize($input);
        $optimizer->initialize($this->dense->weights);
        $optimizer->initialize($this->dense->bias);

        return $this->dense->neurons;
    }

    public function backPropagation(Optimizer $optimizer, Matrix $gradient, RealNumber $epoch): Matrix
    {
        $calibrated = $gradient
            ->pipe(fn($g) => $this->dropout->backPropagation($g))
            ->pipe(fn($g) => $this->sense->backPropagation($g));

        return $this->dense->backPropagation($optimizer, $calibrated, $epoch);
    }

    public function feedForward(Matrix $input): Matrix
    {
        return $input
            ->pipe(fn(Matrix $x): Matrix => $this->dense->feedForward($x))
            ->pipe(fn(Matrix $x): Matrix => $this->sense->feedForward($x))
            ->pipe(fn(Matrix $x): Matrix => $this->dropout->feedForward($x));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'dense' => $this->dense->jsonSerialize(),
                'sense' => $this->sense->jsonSerialize(),
                'dropout' => $this->dropout->jsonSerialize(),
            ],
        ];
    }

    public function touch(Matrix $input): Matrix
    {
        return $input
            ->pipe(fn(Matrix $x): Matrix => $this->dense->touch($x))
            ->pipe(fn(Matrix $x): Matrix => $this->sense->touch($x));
    }
}