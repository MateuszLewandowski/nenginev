<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;

interface Clipable
{
    public function clipToMin(RealNumber $min): Tensor;
    public function clipToMax(RealNumber $max): Tensor;
}
