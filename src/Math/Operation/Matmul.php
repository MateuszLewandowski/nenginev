<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Matrix;

interface Matmul
{
    public function matmul(Matrix $matrix): self;
}