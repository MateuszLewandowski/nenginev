<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Network\Continuous;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Continuous::class)]
final class ContinuousTest extends TestCase
{
    #[DataProvider('inputTensorWithItsLabelProvider')]
    public function testCalculateGradient(
        Matrix $init,
        Matrix $input,
        Scalar $label,
        Scalar $expected,
    ): void {
        $continuous = new Continuous($init, new MeanSquaredError());
        $gradient = $continuous->gradient($input, $label);

        $this->assertSame($expected->primitive(), $gradient->primitive());
    }

    public static function inputTensorWithItsLabelProvider(): Generator
    {
        yield [
            'init' => Matrix::create([
                [0.0, 0.0],
                [0.0, 0.0],
            ]),
            'input' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
            'label' => Scalar::create(1.0),
            'expected' => Scalar::create(7.0),
        ];
    }

    #[DataProvider('backPropagationLabelProvider')]
    public function testBackPropagationResult(
        Matrix $init,
        Scalar $label,
        Scalar $expectedGradient,
        Scalar $expectedLoss,
    ): void {
        $continuous = new Continuous($init, new MeanSquaredError());
        $result = $continuous->backPropagation($label);

        $this->assertSame($expectedGradient->primitive(), $result->gradient->primitive());
        $this->assertSame($expectedLoss->primitive(), $result->loss->primitive());
    }

    public static function backPropagationLabelProvider(): Generator
    {
        yield [
            'init' => Matrix::create([
                [.5, .5],
                [.5, .5],
            ]),
            'label' => Scalar::create(1.0),
            'expectedGradient' => Scalar::create(.5),
            'expectedLoss' => Scalar::create(4.0),
        ];
    }
}