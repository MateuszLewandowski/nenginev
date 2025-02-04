<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;

trait Clipping
{
    /** @default 1e-8 */
    public function clipToMin(RealNumber $min): Tensor
    {
        $this->values->mutate(static fn (float $value, float $min): float => max($min, $value), $min->value);

        return $this;
    }

    /** @default 1.0 */
    public function clipToMax(RealNumber $max): Tensor
    {
        $this->values->mutate(static fn (float $value, float $max): float => min($max, $value), $max->value);

        return $this;
    }
}