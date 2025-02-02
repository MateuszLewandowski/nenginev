<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Tensor;

use App\Math\Tensor\Scalar;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Scalar::class)]
final class ScalarTest extends TestCase
{
    #[DataProvider('scalarInputsProvider')]
    public function testCreateScalarWithValues(float $value): void
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

    public function testIsScalarCompatibleWithOtherTensors(): void
    {
        $this->assertTrue(Scalar::create(1.0)->isCompatible(Scalar::create(.0)));
    }

    public function testScalarSizeIsAlwaysSingleRealNumber(): void
    {
        $this->assertSame(1, Scalar::create(.0)->size());
    }
}