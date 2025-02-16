<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Exception\DropoutCoefficientOutOfRangeException;
use App\ComputationalIntelligence\Parameter;
use App\Math\Tensor\Scalar;

final class Coefficient extends Parameter
{
    private const float MIN = .0;
    private const float MAX = 1.0;

    public function __construct(float $value)
    {
        if ($value < self::MIN || $value > self::MAX) {
            throw new DropoutCoefficientOutOfRangeException(self::MIN, self::MAX);
        }

        parent::__construct($value);
    }

    public function ratio(): Scalar
    {
        return Scalar::create($this->value);
    }

    public function scale(): Scalar
    {
        return Scalar::create(1.0 / (1.0 - $this->value));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'value' => $this->value,
                'ratio' => $this->ratio()->primitive(),
                'scale' => $this->scale()->primitive(),
            ],
        ];
    }
}