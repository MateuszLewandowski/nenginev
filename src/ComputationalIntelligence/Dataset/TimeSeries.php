<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\Exception\CorruptedTimeSeriesException;
use App\Math\Values;

final readonly class TimeSeries
{
    private const string DATETIME_FORMAT = '/^(?:\d{4})-(?:0[1-9]|1[0-2])-(?:0[1-9]|[12]\d|3[01]) (?:[01]\d|2[0-3]):(?:[0-5]\d):(?:[0-5]\d)$/';

    private function __construct(
        private array $data = [],
    ) {
    }

    public static function create(array $data): self
    {
        foreach ($data as $datetime => &$value) {
            if (!is_numeric($value) || !preg_match(self::DATETIME_FORMAT, $datetime)) {
                throw new CorruptedTimeSeriesException();
            }

            $value = (float) $value;
        }

        return new self($data);
    }

    public function values(): Values
    {
        return Values::create($this->data);
    }

    public function primitive(): array
    {
        return $this->data;
    }

    public function length(): int
    {
        return count($this->data);
    }
}