<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ActivationFunction;

use App\Math\Tensor\Matrix;

readonly class Linear extends ActivationFunction
{

    public function compute(Matrix $input): Matrix
    {
        return $input;
    }

    /**
     * Generally, both matrices are needed to compute the gradient correctly.
     * However, for the linear activation function, the derivative is constant (1)
     * and does not depend on the output matrix. Therefore, the output matrix is not used here.
     */
    public function derivative(Matrix $input, Matrix $output): Matrix
    {
        return $input;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
        ];
    }
}