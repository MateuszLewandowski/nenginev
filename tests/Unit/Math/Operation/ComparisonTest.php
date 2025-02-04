<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\Tensor\Vector;
use PHPUnit\Framework\TestCase;

final class ComparisonTest extends TestCase
{
    public function testCompareTwoVectors(): void
    {
        $first = Vector::create([1.0, 2.0]);
        $second = Vector::create([1.0, 3.0]);

        $this->assertSame([1.0, 0.0], $first->equal($second)->primitive());
        $this->assertSame([0.0, 1.0], $first->notEqual($second)->primitive());
        $this->assertSame([0.0, 0.0], $first->greater($second)->primitive());
        $this->assertSame([1.0, 0.0], $first->greaterOrEqual($second)->primitive());
        $this->assertSame([0.0, 1.0], $first->less($second)->primitive());
        $this->assertSame([1.0, 1.0], $first->lessOrEqual($second)->primitive());
    }
}