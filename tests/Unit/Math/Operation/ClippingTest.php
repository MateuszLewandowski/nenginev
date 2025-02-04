<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Vector;
use PHPUnit\Framework\TestCase;

final class ClippingTest extends TestCase
{
    public function testClipVector(): void
    {
        $vectorMax = Vector::create([0.5, 2.0]);
        $expectedMax = [0.5, 1.0];

        $vectorMin = Vector::create([1e-7, 1e-10]);
        $expectedMin = [1e-7, 1e-8];

        $this->assertSame($expectedMax, $vectorMax->clipToMax(RealNumber::one())->primitive());
        $this->assertSame($expectedMin, $vectorMin->clipToMin(RealNumber::epsilon())->primitive());
    }
}