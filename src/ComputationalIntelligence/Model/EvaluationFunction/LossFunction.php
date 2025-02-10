<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\EvaluationFunction;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

/**
 * @important
 *
 * As a loss function, function measures the error for individual training examples.
 * It calculates the difference between the predicted and actual value for each example.
 */
interface LossFunction extends \JsonSerializable
{
    public function differential(Matrix $output, Scalar $target): Scalar;
    public function derivative(Matrix $output, Scalar $target): Matrix;
}