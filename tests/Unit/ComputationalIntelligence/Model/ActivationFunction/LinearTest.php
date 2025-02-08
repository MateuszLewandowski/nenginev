<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\ActivationFunction;

use App\ComputationalIntelligence\Model\ActivationFunction\ActivationFunction;
use App\ComputationalIntelligence\Model\ActivationFunction\Linear;
use App\Math\Tensor\Matrix;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Linear::class)]
final class LinearTest extends TestCase
{
    private ActivationFunction $linear;

    public function setUp(): void
    {
        $this->linear = new Linear();
    }

    #[DataProvider('inputMatricesDataProvider')]
    public function testComputeLinearActivationFunction(Matrix $given, Matrix $expected): void
    {
        $result = $this->linear->compute($given);

        $this->assertSame($expected->primitive(), $result->primitive());
    }

    #[DataProvider('inputMatricesDataProvider')]
    public function testDerivativeLinearActivationFunction(Matrix $given, Matrix $expected): void
    {
        $result = $this->linear->derivative($given, $given);

        $this->assertSame($expected->primitive(), $result->primitive());
    }

    public static function inputMatricesDataProvider(): Generator
    {
        yield 'just no-activation activation function' => [
            'given' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
            'expected' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
        ];
    }
}