<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ComponentProvider;

interface ComponentHashmapProvider
{
    public static function fromHashmap(array $hashmap): mixed;
}