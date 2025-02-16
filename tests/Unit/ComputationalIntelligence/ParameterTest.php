<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence;

use App\ComputationalIntelligence\Model\Exception\NegativeValueException;
use App\ComputationalIntelligence\Parameter;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Parameter::class)]
final class ParameterTest extends TestCase
{
    #[DataProvider('parameterValuesProvider')]
    public function testCreateParameter(float|string|int $value): void
    {
        $parameter = new Parameter($value);

        $this->assertSame((float) $value, $parameter->value);
        $this->assertSame((int) $value, $parameter->asInteger());
    }

    public static function parameterValuesProvider(): Generator
    {
        yield [.0];
        yield ['100'];
        yield [1203];
        yield [0x221];
        yield ['123.445'];
        yield [INF];
    }

    public function testTryToCreateParameterWithNegativeValue(): void
    {
        $this->expectException(NegativeValueException::class);

        new Parameter('-.1');
    }
}