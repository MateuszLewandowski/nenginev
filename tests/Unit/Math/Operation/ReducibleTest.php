<?php

declare(strict_types=1);

namespace App\Tests\Unit\Math\Operation;

use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Vector;
use PHPUnit\Framework\TestCase;

final class ReducibleTest extends TestCase
{
    public function testReducibleVector(): void
    {
        $vector = Vector::create([1.0, 2.0]);

        $this->assertSame(Scalar::create(1.0)->primitive(), $vector->min()->primitive());
        $this->assertSame(Scalar::create(2.0)->primitive(), $vector->max()->primitive());
        $this->assertSame(Scalar::create(2/3)->primitive(), $vector->mean()->primitive());
        $this->assertSame(Scalar::create(3.0)->primitive(), $vector->sum()->primitive());
        $this->assertSame(Scalar::create(array_product($vector->primitive()))->primitive(), $vector->product()->primitive());
    }

    public function testReducibleMatrix(): void
    {
        $matrix = Matrix::create([
            [1.0, 2.0],
            [3.0, 4.0],
        ]);

        $this->assertSame(Vector::create([1.0, 3.0])->primitive(), $matrix->min()->primitive());
        $this->assertSame(Vector::create([2.0, 4.0])->primitive(), $matrix->max()->primitive());
        $this->assertSame(Vector::create([3/2, 7/2])->primitive(), $matrix->mean()->primitive());
        $this->assertSame(Vector::create([3.0, 7.0])->primitive(), $matrix->sum()->primitive());
        $this->assertSame(Vector::create([array_product([1.0, 2.0]), array_product([3.0, 4.0])])->primitive(), $matrix->product()->primitive());
    }
}