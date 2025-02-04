<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

trait Comparison
{
    public function equal(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first === $second));
    }

    public function notEqual(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first !== $second));
    }

    public function greater(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first > $second));
    }

    public function greaterOrEqual(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first >= $second));
    }

    public function less(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first < $second));
    }

    public function lessOrEqual(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => (float) ($first <= $second));
    }
}