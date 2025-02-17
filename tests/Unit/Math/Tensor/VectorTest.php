<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Tensor;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;
use App\Math\Tensor\TensorType;
use App\Math\Tensor\Vector;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Vector::class)]
final class VectorTest extends TestCase
{
    public function testCreatVectorFromList(): void
    {
        $input = [1, 2, 3, 4.5, '11'];
        $length = count($input);
        $vector = Vector::create($input);

        $this->assertSame(array_map(static fn(mixed $value): float => (float) $value, $input), $vector->primitive());
        $this->assertSame($length, $vector->dimension());
        $this->assertSame($vector->dimension(), $vector->size());
        $this->assertSame(TensorType::VECTOR, $vector->type());
        $this->assertTrue($vector->isVector());
    }

    #[DataProvider('compatibleTensorsProvider')]
    public function testColumnVectorCompatibleTensors(Tensor $tensor, bool $isCompatible): void
    {
        $vector = Vector::create([1.0, 2.0]);

        $this->assertSame($isCompatible, $vector->isCompatible($tensor));
    }

    public static function compatibleTensorsProvider(): Generator
    {
        yield ['tensor' => Scalar::random(), 'isCompatible' => true];
        yield ['tensor' => Vector::create(1.0), 'isCompatible' => false];
        yield ['tensor' => Vector::create([1, 2]), 'isCompatible' => true];
        yield ['tensor' => Vector::create([1.0, 2.0, 3.0]), 'isCompatible' => false];
        yield ['tensor' => Matrix::create([
            [1, 2],
            [3, 4]
        ]), 'isCompatible' => true];
        yield ['tensor' => Matrix::create([
            [1, 2],
            [3, 4],
            [5, 6]
        ]), 'isCompatible' => false];
        yield ['tensor' => Matrix::create([
            [1, 2, 3],
            [4, 5, 6]
        ]), 'isCompatible' => true];
    }

    public function testVectorCanBeSerializedToJson(): void
    {
        $input = [1.0, 2.0];

        $this->assertSame(['vector' => $input], Vector::create($input)->jsonSerialize());
    }
}