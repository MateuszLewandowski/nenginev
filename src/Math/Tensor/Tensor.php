<?php

declare(strict_types=1);

namespace App\Math\Tensor;

abstract class Tensor
{
    abstract public static function create(float|array $input): self;
    abstract public function isCompatible(self $tensor): bool;
    abstract public function primitive(): mixed;
    abstract public function size(): int;
}