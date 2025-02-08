<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Exception\NonPositiveNeuronsQuantityException;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\Values;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Neurons::class)]
final class NeuronsTest extends TestCase
{
    #[DataProvider('valuesProvider')]
    public function testCreateNeuronsSet(array $input): void
    {
        $this->assertSame($input, Neurons::create($input)->data());
    }

    public static function valuesProvider(): \Generator
    {
        yield [
            [1.0, 2.0],
        ];
    }

    public function testTryToCreateNeuronsVectorWithNonPositiveQuantity(): void
    {
        $this->expectException(NonPositiveNeuronsQuantityException::class);

        Neurons::create([]);
    }

    #[DataProvider('valuesProvider')]
    public function testNeuronsAsValuesInstance(array $input): void
    {
        $this->assertInstanceOf(Values::class, Neurons::create($input));
    }
}