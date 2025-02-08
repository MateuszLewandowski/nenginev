<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\CostFunction;

use App\ComputationalIntelligence\Model\Exception\DifferentVectorsLengthException;
use App\Math\RealNumber;
use App\Math\Values;

final readonly class MeanSquaredError implements CostFunction
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

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
        ];
    }
}