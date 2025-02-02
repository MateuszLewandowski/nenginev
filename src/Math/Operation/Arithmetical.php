<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

interface Arithmetical
{
    public function add(Tensor $tensor): Tensor;
    public function subtract(Tensor $tensor): Tensor;
    public function multiply(Tensor $tensor): Tensor;
    public function divide(Tensor $tensor): Tensor;
}
