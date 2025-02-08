<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\Math\RealNumber;
use App\Math\Values;

final readonly class MultiPerceptron implements RegressionModel
{

    public function initialize(): void
    {
        // TODO: Implement initialize() method.
    }

    public function train(): RealNumber
    {
        // TODO: Implement train() method.
    }

    public function test(): RealNumber
    {
        // TODO: Implement test() method.
    }

    public function predict(LabeledDataset $features): Values
    {
        // TODO: Implement predict() method.
    }

    public function evaluate(Values $features, Values $labels): RealNumber
    {
        // TODO: Implement evaluate() method.
    }

    public function jsonSerialize(): array
    {
        // TODO: Implement jsonSerialize() method.
    }
}