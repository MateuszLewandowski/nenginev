<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;

interface Trainable
{
    public function gradient(Matrix $input, Scalar $expected): Tensor;
}