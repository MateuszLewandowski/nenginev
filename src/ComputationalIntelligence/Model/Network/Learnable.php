<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Tensor;

interface Learnable
{
    public function gradient(Matrix $weights, Matrix $previousGradient): Tensor;
}