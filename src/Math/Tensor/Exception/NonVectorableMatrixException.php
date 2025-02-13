<?php

declare(strict_types=1);

namespace App\Math\Tensor\Exception;

use RuntimeException;

final class NonVectorableMatrixException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Given matrix is non-vectorable.');
    }
}