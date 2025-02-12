<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\MatrixInitializer;

use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final readonly class He implements Initializer
{
    public function initialize(Neurons $rows, Neurons $columns): Matrix
    {
        return Matrix::random($rows->length(), $columns->length())
            ->multiply(Scalar::create(sqrt(6.0 / ($rows->length() + $columns->length()))));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
        ];
    }
}