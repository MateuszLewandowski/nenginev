<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\ActivationFunction;

use App\ComputationalIntelligence\Model\ActivationFunction\ActivationFunction;
use App\ComputationalIntelligence\Model\ActivationFunction\ReLU;
use App\Math\Tensor\Matrix;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(ReLU::class)]
final class ReLUTest extends TestCase
{
    private ActivationFunction $relu;

    public function setUp(): void
    {
        $this->relu = new ReLU();
    }

    #[DataProvider('inputMatricesDataProvider')]
    public function testDerivativeLinearActivationFunction(Matrix $given, Matrix $expected): void
    {
        $result = $this->relu->derivative($given, $given);

        $this->assertSame($expected->primitive(), $result->primitive());
    }

    public static function inputMatricesDataProvider(): Generator
    {
        yield 'all values are greater than zero' => [
            'given' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
            'expected' => Matrix::create([
                [1.0, 1.0],
                [1.0, 1.0],
            ]),
        ];
        yield 'there are some values are not greater than zero' => [
            'given' => Matrix::create([
                [-1.0, 2.0],
                [-3.0, -4.0],
            ]),
            'expected' => Matrix::create([
                [0.0, 1.0],
                [0.0, 0.0],
            ]),
        ];
    }
}