<?php

declare(strict_types=1);

namespace App\Math\Tensor;

enum TensorType: string
{
    case ROW_VECTOR = 'row vector';
    case COLUMN_VECTOR = 'column vector';
    case MATRIX = 'matrix';
    case SCALAR = 'scalar';

    public function isScalar(): bool
    {
        return $this === self::SCALAR;
    }
}