<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\Tensor\Exception\IncompatibleTensorException;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;
use PHPUnit\Framework\TestCase;

final class ArithmeticTest extends TestCase
{
    private Matrix $matrix;
    private Vector $vector;
    private Scalar $scalar;

    public function setUp(): void
    {
        $this->matrix = Matrix::random();
        $this->vector = Vector::random();
        $this->scalar = Scalar::random();
    }

    public function testAddTensorsWithTheSameTypes(): void
    {
        $matrix = $this->matrix->primitive();
        $vector = $this->vector->primitive();
        $scalar = $this->scalar->primitive();

        $expectedMatrixWithMatrix = [
            [$matrix[0][0] + $matrix[0][0], $matrix[0][1] + $matrix[0][1]],
            [$matrix[1][0] + $matrix[1][0], $matrix[1][1] + $matrix[1][1]],
        ];

        $this->assertSame($expectedMatrixWithMatrix, $this->matrix->add($this->matrix)->primitive());
        $this->assertSame(array_map(static fn(float $value) => $value + $value, $vector), $this->vector->add($this->vector)->primitive());
        $this->assertSame($scalar + $scalar, $this->scalar->add($this->scalar)->primitive());
    }

    public function testAddTensorsWithDifferentTypes(): void
    {
        $matrix = $this->matrix->primitive();
        $vector = $this->vector->primitive();
        $scalar = $this->scalar->primitive();

        $expectedMatrixWithVector = [
            [$matrix[0][0] + $vector[0], $matrix[0][1] + $vector[0]],
            [$matrix[1][0] + $vector[1], $matrix[1][1] + $vector[1]],
        ];

        $expectedMatrixWithScalar = [
            [$matrix[0][0] + $scalar, $matrix[0][1] + $scalar],
            [$matrix[1][0] + $scalar, $matrix[1][1] + $scalar],
        ];

        $this->assertSame($expectedMatrixWithVector, $this->matrix->add($this->vector)->primitive());
        $this->assertSame($expectedMatrixWithVector, $this->vector->add($this->matrix)->primitive());

        $this->assertSame($expectedMatrixWithScalar, $this->matrix->add($this->scalar)->primitive());
        $this->assertSame($expectedMatrixWithScalar, $this->scalar->add($this->matrix)->primitive());
    }

    public function testTryToAddIncompatibleTensors(): void
    {
        $this->expectException(IncompatibleTensorException::class);

        $this->matrix->add(Matrix::create([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9],
        ]));
    }
}