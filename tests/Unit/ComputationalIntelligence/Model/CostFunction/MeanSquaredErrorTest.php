<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\CostFunction;

use App\ComputationalIntelligence\Model\CostFunction\CostFunction;
use App\ComputationalIntelligence\Model\CostFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Exception\DifferentVectorsLengthException;
use App\Math\RealNumber;
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
    public function testCalculateMeanSquaredError(
        Values $predictions,
        Values $labels,
        RealNumber $expected,
    ): void {
        $error = $this->mse->evaluate($predictions, $labels);

        $this->assertTrue($expected->same($error));
    }

    public static function predictionsWithItsLabelsProvider(): Generator
    {
        /** [(1.0 - 1.1)^2 + (2.0 - 2.2)^2] / 2 */
        yield [
            'predictions' => Values::create([1.0, 2.0]),
            'labels' => Values::create([1.1, 2.2]),
            'expected' => RealNumber::create(0.025),
        ];
    }

    public function testTryToCalculateCostWithPredictionAndLabelsDifferentLength(): void
    {
        $this->expectException(DifferentVectorsLengthException::class);

        $predictions = Values::create([1.0]);
        $labels = Values::create([1.0, 2.0]);

        $this->mse->evaluate($predictions, $labels);
    }
}