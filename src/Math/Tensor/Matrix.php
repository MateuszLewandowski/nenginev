<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Operation\Reducible;
use App\Math\RealNumber;
use App\Math\Values;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;

/**
 * @method Matrix add(Tensor $tensor)
 * @method Matrix subtract(Tensor $tensor)
 * @method Matrix multiply(Tensor $tensor)
 * @method Matrix divide(Tensor $tensor)
 * @method Matrix equal(Tensor $tensor)
 * @method Matrix notEqual(Tensor $tensor)
 * @method Matrix greater(Tensor $tensor)
 * @method Matrix greaterOrEqual(Tensor $tensor)
 * @method Matrix less(Tensor $tensor)
 * @method Matrix lessOrEqual(Tensor $tensor)
 * @method Matrix abs()
 * @method Matrix exp()
 * @method Matrix log()
 * @method Matrix round(RealNumber $precision)
 * @method Matrix floor()
 * @method Matrix ceil()
 * @method Matrix negate()
 * @method Matrix square()
 * @method Matrix pow(RealNumber $base)
 * @method Matrix sqrt()
 * @method Matrix sin()
 * @method Matrix asin()
 * @method Matrix cos()
 * @method Matrix acos()
 * @method Matrix tan()
 * @method Matrix atan()
 */
final readonly class Matrix extends Tensor implements
    Reducible
{
    public function __construct(
        Values $values,
    ) {
        parent::__construct($values, TensorType::MATRIX);
    }

    public static function create(float|array $input): Matrix
    {
        return new self(Values::create(is_float($input) ? [[$input]] : $input));
    }

    public function isCompatible(Tensor $tensor): bool
    {
        return match ($tensor->type()) {
            TensorType::SCALAR => true,
            TensorType::VECTOR, TensorType::MATRIX => $this->dimension() === $tensor->dimension(),
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

    public function transpose(): self
    {
        $result = [];
        foreach ($this->primitive() as $i => $row) {
            foreach ($row as $j => $value) {
                $result[$j][$i] = $value;
            }
        }

        return self::create($result);
    }

    public function min(): Vector
    {
        return Vector::create(array_map(static fn (array $values) => min($values), $this->primitive()));
    }

    public function max(): Vector
    {
        return Vector::create(array_map(static fn (array $values) => max($values), $this->primitive()));
    }

    public function mean(): Vector
    {
        return $this->sum()->divide(Scalar::create($this->columns()))->vector();
    }

    public function sum(): Vector
    {
        return Vector::create(array_map(static fn (array $values) => array_sum($values), $this->primitive()));
    }

    public function product(): Vector
    {
        return Vector::create(array_map(static fn (array $values) => array_product($values), $this->primitive()));
    }
}