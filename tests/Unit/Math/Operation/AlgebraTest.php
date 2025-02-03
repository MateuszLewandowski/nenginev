<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;
use PHPUnit\Framework\TestCase;

final class AlgebraTest extends TestCase
{
    private Matrix $matrix;
    private Vector $vector;
    private Scalar $scalar;

    public function setUp(): void
    {
        $this->matrix = Matrix::random();
        $this->vector = Vector::random();
        $this->scalar = Scalar::random();
    }

    public function testNegateValues(): void
    {
        $fn = static function(float &$value): void {
            $value = -$value;
        };

        $matrix = $this->matrix->primitive();
        array_walk_recursive($matrix, $fn);

        $vector = $this->vector->primitive();
        array_walk($vector, $fn);

        $scalar = -$this->scalar->primitive();

        $this->assertSame($this->matrix->negate()->primitive(), $matrix);
        $this->assertSame($this->vector->negate()->primitive(), $vector);
        $this->assertSame($this->scalar->negate()->primitive(), $scalar);
    }

    public function testPowValues(): void
    {
        $base = RealNumber::create(2);

        $fn = static function(float &$value) use ($base): void {
            $value = $value ** $base->value;
        };

        $matrix = $this->matrix->primitive();
        array_walk_recursive($matrix, $fn);

        $vector = $this->vector->primitive();
        array_walk($vector, $fn);

        $scalar = $this->scalar->primitive() ** $base->value;

        $this->assertSame($this->matrix->pow($base)->primitive(), $matrix);
        $this->assertSame($this->vector->pow($base)->primitive(), $vector);
        $this->assertSame($this->scalar->pow($base)->primitive(), $scalar);
    }
}