<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\Tensor\Scalar;
use PHPUnit\Framework\TestCase;

final class TrigonometricTest extends TestCase
{
    public function testTrigonometricFunctionsWithScalar(): void
    {
        $this->assertSame(0.8414709848079, Scalar::create(1.0)->sin()->primitive());
        $this->assertSame(1.5707963267949, Scalar::create(1.0)->asin()->primitive());
        $this->assertSame(0.5403023058681, Scalar::create(1.0)->cos()->primitive());
        $this->assertSame(0.0, Scalar::create(1.0)->acos()->primitive());
        $this->assertSame(1.5574077246549, Scalar::create(1.0)->tan()->primitive());
        $this->assertSame(0.7853981633974, Scalar::create(1.0)->atan()->primitive());
    }
}