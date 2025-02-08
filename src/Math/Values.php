<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Exception\MissingValuesException;
use App\Math\Tensor\Exception\UndefinedValueAddressException;
use JsonSerializable;

final class Values implements JsonSerializable
{
    private readonly int $rows;
    private readonly int $columns;

    private function __construct(
        /** @var float[] */ private iterable $data,
    ) {
        if (is_countable(current($this->data))) {
            $this->rows = count($this->data);
            $this->columns = count(current($this->data));
        } else {
            $this->rows = 1;
            $this->columns = count($this->data);
        }
    }

    public static function create(array $values): self
    {
        if (empty($values)) {
            throw new MissingValuesException();
        }

        array_walk_recursive($values, static function (mixed &$value): void {
            $value = (float) $value;
        });

        return new self($values);
    }

    public function merge(self $values): self
    {
        return new self(array_merge_recursive($this->data, $values->data));
    }

    public function cell(int $i, int $j = 0): float
    {
        if (is_array($this->data[$i])) {
            if (!isset($this->data[$i][$j])) {
                throw new UndefinedValueAddressException();
            }

            return $this->data[$i][$j];
        }

        if (!isset($this->data[$i])) {
            throw new UndefinedValueAddressException();
        }

        return $this->data[$i];
    }

    public function row(int $i): self
    {
        return new self($this->data[$i]);
    }

    public function column(int $i): self
    {
        return new self(array_column($this->data, $i));
    }

    public function rows(): int
    {
        return $this->rows;
    }

    public function columns(): int
    {
        return $this->columns;
    }

    public function size(): int
    {
        return $this->rows * $this->columns;
    }

    public function jsonSerialize(): array
    {
        return [
            'values' => $this->data(),
        ];
    }

    public function data(): array
    {
        return $this->data;
    }

    public function slice(int $offset, ?int $limit = null): self
    {
        return new self(array_slice($this->data, $offset, $limit));
    }

    public function chunk(int $size): array
    {
        return array_map(static fn(array $chunk): Values => new self($chunk), array_chunk($this->data, $size));
    }

    public function mutate(callable $fn, ...$args): void
    {
        array_walk_recursive($this->data, static function (float &$value) use ($fn, $args): void {
            $value = round($fn($value, ...$args), RealNumber::PRECISION);
        });
    }

    public function length(): int
    {
        return $this->rows === 1 ? $this->columns : $this->rows;
    }

    public function empty(): bool
    {
        return empty($this->data);
    }

    public function hasTheSameLength(self $values): bool
    {
        return $this->length() === $values->length();
    }
}