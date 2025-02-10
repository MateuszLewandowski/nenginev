<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\Tensor\Scalar;

interface BackwardPropagatable
{
    public function backPropagation(Scalar $label): Result;
}