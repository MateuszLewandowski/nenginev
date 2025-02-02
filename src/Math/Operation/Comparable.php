<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

interface Comparable
{
    public function equal(Tensor $tensor): Tensor;
    public function notEqual(Tensor $tensor): Tensor;
    public function greater(Tensor $tensor): Tensor;
    public function greaterOrEqual(Tensor $tensor): Tensor;
    public function less(Tensor $tensor): Tensor;
    public function lessOrEqual(Tensor $tensor): Tensor;
}
