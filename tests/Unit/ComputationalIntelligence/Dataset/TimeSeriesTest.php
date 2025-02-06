<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\TimeSeries;
use ArrayIterator;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class TimeSeriesTest extends TestCase
{
    public function testTimeSeriesExtendsArrayIterator(): void
    {
        $this->assertInstanceOf(ArrayIterator::class, new TimeSeries([]));
    }

    #[DataProvider('timeSeriesProvider')]
    public function testFindExtremesInTimeSeries(TimeSeries $timeSeries): void
    {
        $this->assertSame([100.0, 500.0], $timeSeries->extremes());
    }

    public static function timeSeriesProvider(): Generator
    {
        yield [
            new TimeSeries([
                '2024-01-01 12:00' => 100.0,
                '2024-01-01 12:01' => 200.0,
                '2024-01-01 12:31' => 500.0,
            ])
        ];
    }
}