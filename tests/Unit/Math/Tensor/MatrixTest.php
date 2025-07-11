<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Tensor;

use App\Math\Tensor\Matrix;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MatrixTest extends TestCase
{
    public function testCreateMatrix(): void
    {
        $input = [
            [1.0, 2.0],
            [3.0, 4.0],
            [5.0, 6.0],
        ];

        $matrix = Matrix::create($input);

        $this->assertSame($input, $matrix->primitive());
        $this->assertSame(2, $matrix->columns());
        $this->assertSame(3, $matrix->rows());
        $this->assertSame($matrix->dimension(), $matrix->rows());
        $this->assertSame($matrix->rows() * $matrix->columns(), $matrix->size());
    }

    /** @example report of the work and results of the regression model */
    public function testScalarCanBeSerializedToJson(): void
    {
        $input = [
            [1.0, 2.0],
            [3.0, 4.0],
        ];

        $this->assertSame(['matrix' => $input], Matrix::create($input)->jsonSerialize());
    }

    #[DataProvider('matrixShapeProvider')]
    public function testMatrixHasTheSameShape(array $payload, bool $hasTheSameShape): void
    {
        $this->assertSame($hasTheSameShape, Matrix::example()->sameShape(Matrix::create($payload)));
    }

    public static function matrixShapeProvider(): Generator
    {
        yield 'same shape' => [
            [[1.0, 2.0], [3.0, 4.0]],
            true
        ];
        yield 'different shape' => [
            [[1.0, 2.0], [3.0, 4.0], [5.0, 6.0]],
            false
        ];
    }

    public function testTransposeMatrix(): void
    {
        $input = [
            [1.0, 2.0],
            [3.0, 4.0],
            [5.0, 6.0],
        ];
        $expected = [
            [1.0, 3.0, 5.0],
            [2.0, 4.0, 6.0],
        ];

        $matrix = Matrix::create($input);
        $transposed = $matrix->transpose();

        $this->assertSame($matrix->columns(), $transposed->rows());
        $this->assertSame($matrix->rows(), $transposed->columns());
        $this->assertSame($expected, $transposed->primitive());
        $this->assertSame($matrix->primitive(), $transposed->transpose()->primitive());
    }
}