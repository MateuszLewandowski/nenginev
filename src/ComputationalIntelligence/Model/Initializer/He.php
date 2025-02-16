<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Initializer;

use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final readonly class He implements Initializer
{
    private const float ETA = .70710678118;

    public function initialize(Neurons $rows, Neurons $columns): Matrix
    {
        return Matrix::random($rows->quantity(), $columns->quantity())
            ->multiply(Scalar::create(6.0 / ($rows->quantity() + $columns->quantity()) ** self::ETA));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
        ];
    }
}