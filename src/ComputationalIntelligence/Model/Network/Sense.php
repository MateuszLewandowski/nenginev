<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\ActivationFunction\ActivationFunction;
use App\Math\Tensor\Matrix;

final class Sense implements
    Layer,
    FeedForwarding,
    Touchable,
    GradientOrientedBackwardPropagatable
{
    private Matrix $input;
    private Matrix $output;

    public function __construct(
        private readonly ActivationFunction $activationFunction,
    ) {
    }

    public function feedForward(Matrix $input): Matrix
    {
        $this->input = $input;
        $this->output = $this->touch($input);

        return $this->output;
    }

    public function backPropagation(Matrix $gradient): Matrix
    {
        return $this->activationFunction->derivative($this->input, $this->output)
            ->multiply($gradient);
    }

    public function touch(Matrix $input): Matrix
    {
        return $this->activationFunction->compute($input);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'activation function' => $this->activationFunction->jsonSerialize(),
            ],
        ];
    }
}