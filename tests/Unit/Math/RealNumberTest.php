<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math;

use App\Math\Exception\ArithmeticException;
use App\Math\RealNumber;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(RealNumber::class)]
final class RealNumberTest extends TestCase
{
    #[DataProvider('realNumberExamplesProvider')]
    public function testCreateRealNumber(int|float|string $value, float $expected): void
    {
        $realNumber = new RealNumber($value);
        $sameRealNumber = RealNumber::create($value);

        $this->assertSame($expected, $realNumber->value);
        $this->assertSame($expected, $sameRealNumber->value);
    }

    public static function realNumberExamplesProvider(): Generator
    {
        yield [-1, -1.0];
        yield [1, 1.0];
        yield [10000, 10000.0];
        yield [0.0, 0.0];
        yield [INF, INF];
        yield ['100', 100.0];
    }

    public function testCreateRealNumberWithTheMostImportantInits(): void
    {
        $zero = RealNumber::zero();
        $one = RealNumber::one();
        $infinity = RealNumber::infinity();
        $epsilon = RealNumber::epsilon();
        $base = RealNumber::base();

        $this->assertSame(.0, $zero->value);
        $this->assertSame(1.0, $one->value);
        $this->assertSame(INF, $infinity->value);
        $this->assertSame(1e-8, $epsilon->value);
        $this->assertSame(M_E, $base->value);
    }

    public function testMutateRealNumber(): void
    {
        $realNumber = RealNumber::one();
        $negatedRealNumber = $realNumber->negate();

        $this->assertSame(-1.0, $negatedRealNumber->value);
        $this->assertSame(1.0, $negatedRealNumber->abs()->value);
    }

    public function testCompareTwoRealNumbers(): void
    {
        $first = RealNumber::one();
        $second = RealNumber::zero();

        $this->assertFalse($first->equals($second));
        $this->assertTrue($first->greaterThan($second));
        $this->assertTrue($first->greaterOrEquals($second));
        $this->assertFalse($first->lessThan($second));
        $this->assertFalse($first->lessThanOrEquals($second));
    }

    public function testSumTwoRealNumbers(): void
    {
        $expected = 49.65;
        $fist = RealNumber::create(100.15);
        $second = RealNumber::create('-50.5');

        $this->assertSame($expected, $fist->add($second)->value);
    }

    public function testSubtractTwoRealNumbers(): void
    {
        $expected = 49.65;
        $fist = RealNumber::create(100.15);
        $second = RealNumber::create(50.5);

        $this->assertSame($expected, $fist->subtract($second)->value);
    }

    public function testMultiplyTwoRealNumbers(): void
    {
        $expected = -49.0;
        $fist = RealNumber::create(7);
        $second = RealNumber::create('-7');

        $this->assertSame($expected, $fist->multiply($second)->value);
    }

    public function testDivideTwoRealNumbers(): void
    {
        $expected = 7.0;
        $fist = RealNumber::create('49');
        $second = RealNumber::create(7.0);

        $this->assertSame($expected, $fist->divide($second)->value);
    }

    public function testTryToDivideByZero(): void
    {
        $this->expectException(ArithmeticException::class);
        $this->expectExceptionMessage('Division by zero');

        RealNumber::one()->divide(RealNumber::zero());
    }

    public function testIsRealNumberPositive(): void
    {
        $positive = RealNumber::create(.0000001);
        $zero = RealNumber::zero();

        $this->assertTrue($positive->isPositive());
        $this->assertFalse($zero->isPositive());
    }

    public function testConvertRealNumberToItsValueAsStringForPresentationPurposes(): void
    {
        $this->assertSame('1', RealNumber::create(1.0)->__toString());
        $this->assertSame('1.5', RealNumber::create(1.5)->__toString());
        $this->assertSame('-1.5', RealNumber::create(-1.5)->__toString());
    }

    public function testConvertRealNumberToTheReportRepresentation(): void
    {
        $this->assertSame([
            'value' => '1.5',
        ], RealNumber::create(1.5)->jsonSerialize());
    }
}