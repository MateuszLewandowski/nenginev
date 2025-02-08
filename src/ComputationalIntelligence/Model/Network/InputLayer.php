<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Exception\DifferentFeaturesAndNeuronsQuantityException;
use App\Math\Tensor\Matrix;

final readonly class InputLayer implements Layer
{
    public function __construct(
        public Neurons $neurons,
    ) {
    }

    public function touch(Matrix $input): Matrix
    {
        return $input;
    }

    public function feedForward(Matrix $input): Matrix
    {
        if ($input->rows() !== $this->neurons->length()) {
            throw new DifferentFeaturesAndNeuronsQuantityException();
        }

        return $input;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
            'args' => [
                'neurons' => $this->neurons->data(),
            ],
        ];
    }
}