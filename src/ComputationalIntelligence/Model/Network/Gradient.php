<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Tensor;

final readonly class Gradient
{
    public function __construct(
        public Tensor $value,
    ) {
    }
}