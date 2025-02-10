<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\Tensor\Scalar;

interface OptimizedBackwardPropagatable
{
    public function backPropagation(Scalar $label, Optimizer $optimizer): Result;
}