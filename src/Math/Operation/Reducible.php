<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

interface Reducible
{
    public function min(): Tensor;
    public function max(): Tensor;
    public function mean(): Tensor;
    public function sum(): Tensor;
    public function product(): Tensor;
}
