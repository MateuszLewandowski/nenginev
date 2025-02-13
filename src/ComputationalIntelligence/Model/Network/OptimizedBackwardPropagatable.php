<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;

interface OptimizedBackwardPropagatable
{
    public function backPropagation(Optimizer $optimizer, Matrix $gradient, RealNumber $epoch): Matrix;
}