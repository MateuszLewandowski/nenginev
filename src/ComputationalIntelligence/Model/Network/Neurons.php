<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\ComputationalIntelligence\Model\Exception\NonPositiveNeuronsQuantityException;
use App\Math\RealNumber;

class Neurons extends RealNumber
{
    #[\Override]
    public static function create(float|int|string $value): static
    {
        $value = (int) $value;
        if ($value < 1) {
            throw new NonPositiveNeuronsQuantityException();
        }

        return parent::create($value);
    }

    public static function single(): self
    {
        return self::create(1);
    }

    public function quantity(): int
    {
        return (int) $this->value;
    }

    public function hasLengthAs(int $length): bool
    {
        return $this->quantity() === $length;
    }
}