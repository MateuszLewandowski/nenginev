<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;

interface Touchable
{
    public function touch(Matrix $input): Matrix;
}