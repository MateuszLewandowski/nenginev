<?php

declare(strict_types=1);

namespace App\Math;

use App\Math\Exception\ArithmeticException;
use JsonSerializable;
use Stringable;

final readonly class RealNumber implements Stringable, JsonSerializable
{
    private const int PRECISION = 12;
    public float $value;

    public function __construct(int|float|string $value)
    {
        $this->value = round((float) $value, self::PRECISION);
    }

    public static function create(int|float|string $value): self
    {
        return new self($value);
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }

    public static function zero(): self
    {
        return new self(.0);
    }

    public static function one(): self
    {
        return new self(1.0);
    }

    public static function infinity(): self
    {
        return new self(INF);
    }

    public function negate(): self
    {
        return new self(-$this->value);
    }

    public function abs(): self
    {
        return new self(abs($this->value));
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function lessThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function greaterOrEquals(self $other): bool
    {
        return $this->value >= $other->value;
    }

    public function lessThanOrEquals(self $other): bool
    {
        return $this->value <= $other->value;
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value);
    }

    public function subtract(self $other): self
    {
        return new self($this->value - $other->value);
    }

    public function multiply(self $other): self
    {
        return new self($this->value * $other->value);
    }

    public function divide(self $other): self
    {
        if ($other->value === 0.0) {
            throw ArithmeticException::divideByZero();
        }

        return new self($this->value / $other->value);
    }

    public function isPositive(): bool
    {
        return $this->value > .0;
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->__toString(),
        ];
    }
}