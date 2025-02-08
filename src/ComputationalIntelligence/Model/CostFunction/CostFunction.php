<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\CostFunction;

use App\Math\RealNumber;
use App\Math\Values;

interface CostFunction extends \JsonSerializable
{
    public function evaluate(Values $predictions, Values $labels): RealNumber;
}