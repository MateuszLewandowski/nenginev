<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;

interface Layer extends \JsonSerializable
{
    public function touch(Matrix $input): Matrix;
    public function feedForward(Matrix $input): Matrix;
}