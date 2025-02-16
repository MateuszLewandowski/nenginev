<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Exception\NonPositiveNeuronsQuantityException;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\RealNumber;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Neurons::class)]
final class NeuronsTest extends TestCase
{
    #[DataProvider('valuesProvider')]
    public function testCreateNeuronsSet(int|string|float $input): void
    {
        $this->assertSame((float) $input, Neurons::create($input)->value);
    }

    public static function valuesProvider(): \Generator
    {
        yield [17];
        yield ['17'];
        yield [17.0];

    }

    public function testTryToCreateNeuronsVectorWithNonPositiveQuantity(): void
    {
        $this->expectException(NonPositiveNeuronsQuantityException::class);

        Neurons::create(0);
    }

    #[DataProvider('valuesProvider')]
    public function testNeuronsAsValuesInstance(int|string|float $input): void
    {
        $this->assertInstanceOf(RealNumber::class, Neurons::create($input));
    }
}