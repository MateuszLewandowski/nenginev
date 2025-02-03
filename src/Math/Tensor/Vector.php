<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Values;

final readonly class Vector extends Tensor
{
    public function __construct(
        private Values $values,
    ) {
        parent::__construct(TensorType::VECTOR);
    }

    public static function create(float|array $input): Vector
    {
        return new self(Values::create(is_float($input) ? [$input] : $input));
    }

    public function isCompatible(Tensor $tensor): bool
    {
        return match ($tensor->type()) {
            TensorType::SCALAR => true,
            TensorType::VECTOR => $this->size() === $tensor->size(),
            TensorType::MATRIX => $this->dimension() === $tensor->dimension(),
        };
    }

    public function size(): int
    {
        return $this->values->size();
    }

    public function jsonSerialize(): array
    {
        return [
            'vector' => $this->values->data(),
        ];
    }

    public function values(): Values
    {
        return $this->values;
    }

    public function dimension(): int
    {
        return $this->size();
    }

    public function primitive(): array
    {
        return $this->values->data();
    }
}