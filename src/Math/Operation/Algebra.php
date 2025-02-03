<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;

trait Algebra
{
    public function abs(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function exp(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function log(RealNumber $base): Tensor
    {
        $this->values->mutate(__FUNCTION__, $base->value);

        return $this;
    }

    public function round(RealNumber $precision): Tensor
    {
        $this->values->mutate(__FUNCTION__, $precision->value);

        return $this;
    }

    public function floor(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function ceil(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function negate(): Tensor
    {
        $this->values->mutate(static fn (float $value): float => -$value);

        return $this;
    }

    public function square(): Tensor
    {
        $this->values->mutate(static fn (float $value): float => $value ** 2);

        return $this;
    }

    public function pow(RealNumber $base): Tensor
    {
        $this->values->mutate(static fn (float $value, float $base): float => $value ** $base, $base->value);

        return $this;
    }

    public function sqrt(): Tensor
    {
        $this->values->mutate(static fn (float $value): float => sqrt($value));

        return $this;
    }
}