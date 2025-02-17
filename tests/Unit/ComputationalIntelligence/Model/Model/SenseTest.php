<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\ActivationFunction\ReLU;
use App\ComputationalIntelligence\Model\Network\Sense;
use App\Math\Tensor\Matrix;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Sense::class)]
final class SenseTest extends TestCase
{
    #[DataProvider('senseForLinearActivationFunctionComputingProvider')]
    public function testFeedForward(Matrix $input, Sense $sense, array $expected): void
    {
        $output = $sense->feedForward($input);

        # ReLU has linear activation function
        $this->assertSame($expected, $output->primitive());
    }

    public static function senseForLinearActivationFunctionComputingProvider(): Generator
    {
        $example = [
            [-1.0, 1.0],
            [-2.0, 2.0],
        ];

        yield [
            'input' => Matrix::create($example),
            'sense' => new Sense(new ReLU()),
            'expected' => $example,
        ];
    }

    #[DataProvider('senseForReluActivationFunctionDerivativeProvider')]
    public function testBackPropagationWhichCalculateDerivative(Matrix $input, Sense $sense, array $expected): void
    {
        $sense->feedForward($input);
        $gradient = $sense->backPropagation($input);

        $this->assertSame($expected, $gradient->primitive());
    }

    public static function senseForReluActivationFunctionDerivativeProvider(): Generator
    {
        yield [
            'input' => Matrix::create([
                [-1.0, 1.0],
                [-2.0, 2.0],
            ]),
            'sense' => new Sense(new ReLU()),
            'expected' => [
                [.0, 1.0],
                [.0, 2.0],
            ],
        ];
    }
}