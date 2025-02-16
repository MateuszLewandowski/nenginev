<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Exception\DifferentFeaturesAndNeuronsQuantityException;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;

final readonly class Stream implements
    Layer,
    FeedForwarding
{
    public function __construct(
        public Neurons $neurons,
    ) {
    }

    public function feedForward(Matrix $input): Matrix
    {
        if (!$this->neurons->hasLengthAs($input->rows())) {
            throw new DifferentFeaturesAndNeuronsQuantityException();
        }

        return $input;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
            'args' => [
                'neurons' => $this->neurons->value,
            ],
        ];
    }

    public function neuronsQuantity(): RealNumber
    {
        return new RealNumber($this->neurons->value);
    }
}