<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;

interface FeedForwarding
{
    public function feedForward(Matrix $input): Matrix;
}