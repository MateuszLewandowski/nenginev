<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

final readonly class Datasets
{
    /** @var Dataset[] */ private array $value;

    public function __construct(Dataset ...$dataset)
    {
        $this->value = $dataset;
    }

    public function values(): array
    {
        return $this->value;
    }

    public function length(): int
    {
        return count($this->value);
    }

    public function empty(): bool
    {
        return empty($this->value);
    }
}