<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\CostFunction;

use App\ComputationalIntelligence\Model\EvaluationFunction\CostFunction;
use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Exception\DifferentVectorsLengthException;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;
use App\Math\Values;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(MeanSquaredError::class)]
final class MeanSquaredErrorTest extends TestCase
{
    private readonly CostFunction $mse;
    public function setUp(): void
    {
        $this->mse = new MeanSquaredError();
    }

    #[DataProvider('predictionsWithItsLabelsProvider')]
    public function testEvaluateMeanSquaredError(
        Values $predictions,
        Values $labels,
        RealNumber $expected,
    ): void {
        $error = $this->mse->evaluate($predictions, $labels);

        $this->assertTrue($expected->same($error));
    }

    public static function predictionsWithItsLabelsProvider(): Generator
    {
        yield [
            'predictions' => Values::create([1.0, 2.0]),
            'labels' => Values::create([1.1, 2.2]),
            'expected' => RealNumber::create(0.025),
        ];
    }

    public function testTryToEvaluateWithPredictionAndLabelsDifferentLength(): void
    {
        $this->expectException(DifferentVectorsLengthException::class);

        $predictions = Values::create([1.0]);
        $labels = Values::create([1.0, 2.0]);

        $this->mse->evaluate($predictions, $labels);
    }

    #[DataProvider('networkOutputWithItsTargetForDifferentialCalculation')]
    public function testCalculateDifferentialBetweenOutputAndTarget(Matrix $output, Scalar $target, RealNumber $expected): void
    {
        $diff = $this->mse->differential($output, $target);

        $this->assertSame($expected->value, round($diff->primitive(), RealNumber::PRECISION));
    }

    public static function networkOutputWithItsTargetForDifferentialCalculation(): Generator
    {
        yield [
            'output' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
            'target' => Scalar::create(1.0),
            'expected' => RealNumber::create(0.2857142857143),
        ];
    }

    #[DataProvider('networkOutputWithItsTargetForDerivativeCalculation')]
    public function testCalculateDerivativeBetweenOutputAndTarget(Matrix $output, Scalar $target, Matrix $expected): void
    {
        $derivative = $this->mse->derivative($output, $target);

        $this->assertSame($expected->primitive(), $derivative->primitive());
    }

    public static function networkOutputWithItsTargetForDerivativeCalculation(): Generator
    {
        yield [
            'output' => Matrix::create([
                [1.0, 2.0],
                [3.0, 4.0],
            ]),
            'target' => Scalar::create(1.0),
            'expected' => Matrix::create([
                [0.0, 2.0],
                [4.0, 6.0],
            ]),
        ];
    }
}