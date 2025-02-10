<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;

final readonly class Result
{
    public function __construct(
        public Tensor $gradient,
        public Scalar $loss,
    ) {
    }
}