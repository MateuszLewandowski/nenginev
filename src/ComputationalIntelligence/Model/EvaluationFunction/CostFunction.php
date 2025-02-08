<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\EvaluationFunction;

use App\Math\RealNumber;
use App\Math\Values;

/**
 * @important
 *
 * As a cost function, function aggregates the errors over the entire training dataset.
 * It calculates the average of the differences between the predicted and actual values for all examples in the dataset.
 */
interface CostFunction extends \JsonSerializable
{
    public function evaluate(Values $predictions, Values $labels): RealNumber;
}