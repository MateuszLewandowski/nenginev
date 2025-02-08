<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Exception\NonPositiveNeuronsQuantityException;
use App\Math\Values;

class Neurons extends Values
{
    #[\Override]
    public static function create(array $values): static
    {
        if (count($values) < 1) {
            throw new NonPositiveNeuronsQuantityException();
        }

        return parent::create($values);
    }
}