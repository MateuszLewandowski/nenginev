<?php

declare(strict_types=1);

namespace App\Math\Operation;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;

final readonly class Compute
{
    public static function matrices(Matrix $first, Matrix $second, callable $fn): Matrix
    {
        $a = $first->primitive();
        $b = $second->primitive();
        $result = [];

        for ($i = 0, $rows = $first->rows(); $i < $rows; ++$i) {
            $result[$i] = [];
            for ($j = 0, $cols = $first->columns(); $j < $cols; ++$j) {
                $result[$i][$j] = $fn($a[$i][$j], $b[$j][$i]);
            }
        }

        return Matrix::create($result);
    }

    public static function matrixWithVector(Matrix $matrix, Vector $vector, callable $fn): Matrix
    {
        $a = $matrix->primitive();
        $b = $vector->primitive();
        $result = [];

        for ($i = 0, $rows = $matrix->rows(); $i < $rows; ++$i) {
            for ($j = 0, $columns = $matrix->columns(); $j < $columns; ++$j) {
                $result[$i][$j] = $fn($a[$i][$j], $b[$i]);
            }
        }

        return Matrix::create($result);
    }

    public static function matrixWithScalar(Matrix $matrix, Scalar $scalar, callable $fn): Matrix
    {
        $a = $matrix->primitive();
        $result = [];

        for ($i = 0, $rows = $matrix->rows(); $i < $rows; ++$i) {
            for ($j = 0, $columns = $matrix->columns(); $j < $columns; ++$j) {
                $result[$i][$j] = $fn($a[$i][$j], $scalar->primitive());
            }
        }

        return Matrix::create($result);
    }

    public static function vectors(Vector $first, Vector $second, callable $fn): Vector
    {
        $a = $first->primitive();
        $b = $second->primitive();
        $result = [];

        for ($i = 0, $length = $first->size(); $i < $length; ++$i) {
            $result[$i] = $fn($a[$i], $b[$i]);
        }

        return Vector::create($result);
    }

    public static function vectorWithScalar(Vector $vector, Scalar $scalar, callable $fn): Vector
    {
        $result = [];
        $a = $vector->primitive();
        $b = $scalar->primitive();

        for ($i = 0, $length = $vector->size(); $i < $length; ++$i) {
            $result[$i] = $fn($a[$i], $b);
        }

        return Vector::create($result);
    }

    public static function scalars(Scalar $first, Scalar $second, callable $fn): Scalar
    {
        return Scalar::create([
            $fn($first->primitive(), $second->primitive()),
        ]);
    }
}