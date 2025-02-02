<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Tensor;

interface Algebraic
{
    public function abs(): Tensor;

    public function exp(): Tensor;

    /** @see https://www.php.net/manual/en/math.constants.php */
    public function log(RealNumber $base): Tensor;

    public function floor(): Tensor;

    public function ceil(): Tensor;

    public function negate(): Tensor;

    public function square(): Tensor;

    public function pow(RealNumber $base): Tensor;

    public function sqrt(): Tensor;
}
