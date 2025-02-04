<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

trait Arithmetic
{
    public function add(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => $first + $second);
    }

    public function subtract(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => $first - $second);
    }

    public function multiply(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => $first * $second);
    }

    public function divide(Tensor $tensor): Tensor
    {
        return $this->fn($this, $tensor, static fn (float $first, float $second): float => $first / $second);
    }
}