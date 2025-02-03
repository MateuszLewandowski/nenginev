<?php

declare(strict_types=1);

namespace App\Math\Tensor;

enum TensorType: string
{
    case VECTOR = 'vector';
    case MATRIX = 'matrix';
    case SCALAR = 'scalar';

    public function isScalar(): bool
    {
        return $this === self::SCALAR;
    }

    public function isVector(): bool
    {
        return $this === self::VECTOR;
    }

    public function isMatrix(): bool
    {
        return $this === self::MATRIX;
    }
}