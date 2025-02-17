<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset\Converter;

use App\ComputationalIntelligence\Dataset\Converter\Normalizer;
use App\ComputationalIntelligence\Dataset\TimeSeries;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Normalizer::class)]
final class NormalizerTest extends TestCase
{
    private Normalizer $normalizer;

    public function setUp(): void
    {
        $this->normalizer = new Normalizer();
    }

    #[DataProvider('timeSeriesScalingProvider')]
    public function testMinMaxFeatureScaling(array $expected): void
    {
        [$expectedValues, $expectedNormalizedValues] = [array_column($expected, 0), array_column($expected, 1)];

        $normalizedValues = $this->normalizer->minMaxFeatureScaling(
            new TimeSeries([
                '2024-01-01 12:00' => 100.0,
                '2024-01-01 12:01' => 200.0,
                '2024-01-01 12:31' => 500.0,
            ])
        );
        $values = $this->normalizer->minMaxFeatureDescaling($normalizedValues);

        $this->assertSame($expectedNormalizedValues, array_values($normalizedValues->getArrayCopy()));
        $this->assertSame($expectedValues, array_values($values->getArrayCopy()));
    }

    public static function timeSeriesScalingProvider(): Generator
    {
        yield 'normalize the values to the interval from 0.0 to 1.0' => [
            [
                [100.00, 0.0],
                [200.00, 0.25],
                [500.00, 1.0],
            ]
        ];
    }
}