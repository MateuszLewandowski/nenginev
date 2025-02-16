<?php

declare(strict_types=1);

namespace App\Math\Tensor\Exception;

use RuntimeException;

final class NonMatrixInputException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Input must be a matrix.');
    }
}