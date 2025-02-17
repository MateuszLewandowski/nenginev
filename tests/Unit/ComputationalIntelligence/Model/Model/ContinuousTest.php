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
    #[DataProvider('backPropagationLabelProvider')]
    public function testBackPropagationResult(
        Scalar $label,
        Matrix $expectedGradient,
        Scalar $expectedLoss,
    ): void {
        $continuous = new Continuous(new MeanSquaredError());
        $continuous->feedForward($expectedGradient);
        $result = $continuous->backPropagation($label);

        $this->assertSame($expectedGradient->primitive(), $result->gradient->primitive());
        $this->assertSame($expectedLoss->primitive(), $result->loss->primitive());
    }

    public static function backPropagationLabelProvider(): Generator
    {
        yield [
            'label' => Scalar::create(1.0),
            'expectedGradient' => Matrix::create([
                [0.0, 1.0],
                [2.0, 3.0],
            ]),
            'expectedLoss' => Scalar::create(0.2857142857142857),
        ];
    }
}