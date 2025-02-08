<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\Math\RealNumber;
use App\Math\Values;

interface RegressionModel extends \JsonSerializable
{
    public function initialize(): void;
    public function train(): RealNumber;
    public function test(): RealNumber;
    public function predict(LabeledDataset $features): Values;
    public function evaluate(Values $features, Values $labels): RealNumber;
}