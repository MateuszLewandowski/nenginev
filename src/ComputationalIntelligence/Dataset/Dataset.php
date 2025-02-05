<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

use App\Math\RealNumber;
use App\Math\Values;

abstract readonly class Dataset
{
    public function __construct(
        protected Values $samples
    ) {
    }

    abstract public function split(RealNumber $ratio): array;

    abstract public function stack(Datasets $datasets): self;

    abstract public function batch(RealNumber $quantity): array;

    abstract public function randomize(): self;

    abstract public function isLabeled(): bool;

    abstract public function isUnlabeled(): bool;

    public function samples(): Values
    {
        return $this->samples;
    }

    public function sample(int $i): Values
    {
        return $this->samples->row($i);
    }
}