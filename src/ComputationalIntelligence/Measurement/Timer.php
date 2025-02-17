<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Measurement;

use App\ComputationalIntelligence\Memory;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

final readonly class Timer implements JsonSerializable
{
    private Uuid $id;
    private Memory $memory;

    public function __construct(
        private TimeUnit $timeUnit,
    ) {
        $this->id = Uuid::v7();
        $this->memory = new Memory();
    }

    public static function createWithSecondScope(): self
    {
        return new self(TimeUnit::SECONDS);
    }

    public function stamp(): void
    {
        $this->memory->push($this->id, $this->timeUnit->cast(hrtime(true)));
    }

    public function pull(): mixed
    {
        $data = $this->memory->get($this->id);
        $this->memory->remove($this->id);

        return $data;
    }

    public function measurement(): string
    {
        return sprintf('%s%s', $this->calculateTotal(), $this->timeUnit->getName());
    }

    private function calculateTotal(): float
    {
        $start = $this->memory->first($this->id);
        $stop = $this->memory->last($this->id);

        return $stop - $start;
    }

    public function jsonSerialize(): array
    {
        return [
            'total' => $this->calculateTotal(),
            'unit' => $this->timeUnit->getName(),
        ];
    }
}