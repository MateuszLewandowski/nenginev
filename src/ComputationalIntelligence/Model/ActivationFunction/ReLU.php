<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ActivationFunction;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final readonly class ReLU extends Linear
{
    #[\Override]
    public function derivative(Matrix $input, Matrix $output): Matrix
    {
        return $input->greater(Scalar::create(.0));
    }
}