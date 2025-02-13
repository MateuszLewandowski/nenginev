<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Optimizer;

use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Tensor;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Adam::class)]
final class AdamTest extends TestCase
{
    private readonly Optimizer $adam;

    public function setUp(): void
    {
        // default
        $this->adam = new Adam(
            learningRate: null, //1e-4
            momentum: null, // 1e-1
            decay: null // 1e-3
        );
    }


    public function testInitializeMemory(): void
    {
        $expected = [
            [0.0, 0.0],
            [0.0, 0.0],
        ];

        $tensor = Matrix::example();
        $this->adam->initialize($tensor);

        $id = $tensor->id();
        [$velocity, $norm] = $this->adam->memory()->get($id);

        $this->assertInstanceOf(Tensor::class, $velocity);
        $this->assertInstanceOf(Tensor::class, $norm);
        $this->assertEquals($expected, $velocity->primitive());
        $this->assertEquals($expected, $norm->primitive());
    }

    public function testOptimizeUpdatesTensor(): void
    {
        $gradient = Matrix::create([
            [.1, -.2],
            [.15, .01],
        ]);
        $epoch = RealNumber::create(1);

        $this->adam->initialize($gradient);

        $output1 = $this->adam->optimize($gradient->id(), $gradient, $epoch);

        $epoch = RealNumber::create(2);
        $output2 = $this->adam->optimize($gradient->id(), $gradient, $epoch);

        $this->assertInstanceOf(Tensor::class, $output1);
        $this->assertInstanceOf(Tensor::class, $output2);
        $this->assertSame([2, 2], $output1->shape());
        $this->assertSame([2, 2], $output2->shape());

        $this->assertNotEquals($output1->primitive(), $output2->primitive());
    }

    public function testJsonSerialization(): void
    {
        $this->assertSame([
            'type' => Adam::class,
            'args' => [
                'learningRate' => 1e-4,
                'momentum' => 1e-1,
                'decay' => 1e-3
            ]
        ], $this->adam->jsonSerialize());
    }
}