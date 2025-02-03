<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Values;
use App\Model\Math\Value;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

final readonly class Matrix extends Tensor
{
    public function __construct(
        private Values $values,
    ) {
        parent::__construct(TensorType::MATRIX);
    }

    public static function create(float|array $input): Matrix
    {
        return new self(Values::create(is_float($input) ? [[$input]] : $input));
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
        return $this->rows() * $this->columns();
    }

    public function rows(): int
    {
        return $this->values->rows();
    }

    public function columns(): int
    {
        return $this->values->columns();
    }

    public function jsonSerialize(): array
    {
        return [
            'matrix' => $this->values->data()
        ];
    }

    public function values(): Values
    {
        return $this->values;
    }

    public function sameShape(Matrix $matrix): bool
    {
        return $this->rows() === $matrix->rows()
            && $this->columns() === $matrix->columns();
    }

    public function dimension(): int
    {
        return $this->values->columns();
    }

    #[WhenNot('production')]
    /* @return Matrix [[1.0, 2.0], [3.0, 4.0]] */
    public static function random(): Matrix
    {
        return self::create([[1.0, 2.0], [3.0, 4.0]]);
    }

    public function primitive(): array
    {
        return $this->values->data();
    }
}