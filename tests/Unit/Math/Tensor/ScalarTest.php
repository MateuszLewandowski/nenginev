<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Tensor;

use App\Math\Tensor\Scalar;
use App\Math\Tensor\TensorType;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Scalar::class)]
final class ScalarTest extends TestCase
{
    #[DataProvider('scalarInputsProvider')]
    public function testCreateScalarWithDifferentValues(float $value): void
    {
        $this->assertSame($value, Scalar::create($value)->primitive());
    }

    public static function scalarInputsProvider(): Generator
    {
        yield [-1.0];
        yield [0.0];
        yield [1.0];
        yield [-0.0];
        yield [INF];
    }

    public function testScalarCompatibilityWithOtherTensors(): void
    {
        $this->assertTrue(Scalar::random()->isCompatible(Scalar::random()));
    }

    public function testScalarSizeIsAlwaysOne(): void
    {
        $this->assertSame(1, Scalar::random()->size());
    }

    public function testScalarReturnsPrimitiveValue(): void
    {
        $scalar = Scalar::random();

        $this->assertSame($scalar->value()->value, $scalar->primitive());
    }

    public function testScalarReturnsCorrectTensorType(): void
    {
        $scalar = Scalar::random();

        $this->assertSame(TensorType::SCALAR, $scalar->type());
        $this->assertTrue($scalar->isScalar());
    }

    /** @example report of the work and results of the regression model */
    public function testScalarCanBeSerializedToJson(): void
    {
        $this->assertSame([
            'scalar' => [
                'value' => '1.5',
            ],
        ], Scalar::create(1.5)->jsonSerialize());
    }
}