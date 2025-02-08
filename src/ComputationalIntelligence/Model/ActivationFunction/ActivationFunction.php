<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\ActivationFunction;

use App\Math\Tensor\Matrix;
use JsonSerializable;

abstract readonly class ActivationFunction implements JsonSerializable
{
    abstract public function compute(Matrix $input): Matrix;
    abstract public function derivative(Matrix $input, Matrix $output): Matrix;

    protected function fn(Matrix $matrix, callable $fn): Matrix
    {
        return Matrix::create(
            array_map(
                static function (array $row) use ($fn): array {
                    foreach ($row as &$value) {
                        $value = $fn($value);
                    }

                    return $row;
                },
                $matrix->values()->data()
            )
        );
    }
}