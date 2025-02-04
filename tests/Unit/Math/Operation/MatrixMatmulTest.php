<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\Tensor\Exception\IncompatibleTensorException;
use App\Math\Tensor\Matrix;
use PHPUnit\Framework\TestCase;

final class MatrixMatmulTest extends TestCase
{
    public function testMatmulMatricesWithTheSameRowsAndColumnsQuantity(): void
    {
        $first = Matrix::create([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);
        $second = Matrix::create([
            [5.0, 6.0],
            [7.0, 8.0],
        ]);
        $expected = [
            [19.0, 22.0],
            [43.0, 50.0],
        ];

        $this->assertSame($expected, $first->matmul($second)->primitive());
    }

    public function testMatmulMatricesWithDifferentColumnsQuantity(): void
    {
        $first = Matrix::create([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);
        $second = Matrix::create([
            [5.0, 6.0, 7.0],
            [8.0, 9.0, 10.0],
        ]);
        $expected = [
            [21.0, 24.0, 27.0],
            [47.0, 54.0, 61.0],
        ];

        $this->assertSame($expected, $first->matmul($second)->primitive());
    }

    public function testTryToMatmulIncompatibleMatrices(): void
    {
        $this->expectException(IncompatibleTensorException::class);

        $first = Matrix::create([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);
        $second = Matrix::create([
            [5.0, 6.0],
            [8.0, 9.0],
            [8.0, 23.0],
        ]);

        $first->matmul($second);
    }
}