<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;

interface GradientOrientedBackwardPropagatable
{
    public function backPropagation(Matrix $gradient): Gradient;
}