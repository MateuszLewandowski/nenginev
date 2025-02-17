<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Operation\Matmul;
use App\Math\Operation\Reducible;
use App\Math\RealNumber;
use App\Math\Tensor\Exception\IncompatibleTensorException;
use App\Math\Tensor\Exception\NonMatrixInputException;
use App\Math\Tensor\Exception\NonVectorableMatrixException;
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
final class Matrix extends Tensor implements
    Reducible,
    Matmul
{
    public function __construct(
        Values $values,
    ) {
        parent::__construct($values, TensorType::MATRIX);
    }

    public static function create(float|array $input): Matrix
    {
        if (!is_array($input) || !is_array(current($input))) {
            throw new NonMatrixInputException();
        }

        return new self(Values::create($input));
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
        return $this->values->rows();
    }

    #[WhenNot('production')]
    /* @return Matrix [[1.0, 2.0], [3.0, 4.0]] */
    public static function example(): Matrix
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

        # here
        return new self(Values::create($result));
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

    public function matmul(Matrix $matrix): Matrix
    {
        $firstMatrixRows = $this->rows();
        $firstMatrixColumns = $this->columns();
        $secondMatrixColumns = $matrix->columns();

        if ($firstMatrixColumns !== $matrix->rows()) {
            throw new IncompatibleTensorException($this, $matrix);
        }

        $first = $this->primitive();
        $second = $matrix->primitive();

        $result = array_fill(0, $firstMatrixRows, array_fill(0, $secondMatrixColumns, 0.0));

        for ($i = 0; $i < $firstMatrixRows; ++$i) {
            for ($j = 0; $j < $secondMatrixColumns; ++$j) {
                for ($k = 0; $k < $firstMatrixColumns; ++$k) {
                    $result[$i][$j] += $first[$i][$k] * $second[$k][$j];
                }
            }
        }

        return self::create($result);
    }

    public static function zeros(int ...$dimensions): self
    {
        [$rows, $columns] = $dimensions;

        return self::create(
            array_fill(
                0,
                $rows,
                array_fill(0, $columns, .0)
            )
        );
    }

    public function shape(): array
    {
        return [
            $this->rows(), $this->columns()
        ];
    }

    public static function random(int $rows, int $columns): Matrix
    {
        return self::create(
            array_map(
                static fn (): array => array_map(
                    static fn (): float => mt_rand() / mt_getrandmax(),
                    range(0, $columns - 1)
                ),
                range(0, $rows - 1)
            )
        );
    }

    public function asVector(): Vector
    {
        return Vector::create($this->values->column(0)->data());
    }
}