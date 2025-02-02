<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

interface Trigonometrical
{
    public function sin(): Tensor;
    public function asin(): Tensor;
    public function cos(): Tensor;
    public function acos(): Tensor;
    public function tan(): Tensor;
    public function atan(): Tensor;
}
