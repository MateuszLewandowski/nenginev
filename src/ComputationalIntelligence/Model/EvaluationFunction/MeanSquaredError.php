<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\EvaluationFunction;

use App\ComputationalIntelligence\Model\Exception\DifferentVectorsLengthException;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Values;

final readonly class MeanSquaredError implements CostFunction, LossFunction
{
    public function evaluate(Values $predictions, Values $labels): RealNumber
    {
        if (!$predictions->hasTheSameLength($labels)) {
            throw new DifferentVectorsLengthException();
        }

        $error = RealNumber::zero();
        foreach ($labels->data() as $i => $label) {
            $error = $error->add(new RealNumber(($label - $predictions->cell($i)) ** 2));
        }

        return $error->divide(new RealNumber($labels->length()));
    }

    public function differential(Matrix $output, Scalar $target): Scalar
    {
        return $output->subtract($target)
            ->square()
            ->mean()
            ->mean();
    }

    public function derivative(Matrix $output, Scalar $target): Matrix
    {
        return $output->subtract($target)->multiply(Scalar::create(2.0));
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
        ];
    }
}