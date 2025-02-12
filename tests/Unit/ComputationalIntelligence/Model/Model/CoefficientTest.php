<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Exception\DropoutCoefficientOutOfRangeException;
use App\ComputationalIntelligence\Model\Network\Coefficient;
use PHPUnit\Framework\TestCase;

final class CoefficientTest extends TestCase
{
    public function testValidCoefficient(): void
    {
        $coefficient = new Coefficient(0.5);

        $this->assertInstanceOf(Coefficient::class, $coefficient);
        $this->assertEquals(0.5, $coefficient->ratio()->primitive());
        $this->assertEquals(2.0, $coefficient->scale()->primitive());
    }

    public function testMinimumValidCoefficient(): void
    {
        $coefficient = new Coefficient(0.0);

        $this->assertEquals(0.0, $coefficient->ratio()->primitive());
        $this->assertEquals(1.0, $coefficient->scale()->primitive());
    }

    public function testMaximumValidCoefficient(): void
    {
        $coefficient = new Coefficient(1.0);

        $this->assertEquals(1.0, $coefficient->ratio()->primitive());
    }

    public function testCoefficientBelowMinimumThrowsException(): void
    {
        $this->expectException(DropoutCoefficientOutOfRangeException::class);
        new Coefficient(-0.1);
    }

    public function testCoefficientAboveMaximumThrowsException(): void
    {
        $this->expectException(DropoutCoefficientOutOfRangeException::class);
        new Coefficient(1.1);
    }

    public function testJsonSerialization(): void
    {
        $coefficient = new Coefficient(0.3);
        $expectedJson = [
            'type' => Coefficient::class,
            'args' => [
                'value' => 0.3,
                'ratio' => 0.3,
                'scale' => 1.4285714285714286,
            ],
        ];

        $this->assertEquals($expectedJson, $coefficient->jsonSerialize());
    }
}