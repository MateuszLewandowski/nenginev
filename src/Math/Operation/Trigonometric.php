<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Tensor;

trait Trigonometric
{
    public function sin(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function asin(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function cos(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function acos(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function tan(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }

    public function atan(): Tensor
    {
        $this->values->mutate(__FUNCTION__);

        return $this;
    }
}