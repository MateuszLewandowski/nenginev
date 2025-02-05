<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\Exception\CorruptedTimeSeriesException;
use App\ComputationalIntelligence\Dataset\TimeSeries;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(TimeSeries::class)]
#[CoversClass(CorruptedTimeSeriesException::class)]
final class TimeSeriesTest extends TestCase
{
    #[DataProvider('timeSeriesDataProvider')]
    public function testCreateTimeSeries(array $timeSeriesPayload): void
    {
        $timeSeries = TimeSeries::create($timeSeriesPayload);

        $this->assertSame($timeSeriesPayload, $timeSeries->values()->data());
        $this->assertSame(count($timeSeriesPayload), $timeSeries->length());
    }

    public static function timeSeriesDataProvider(): Generator
    {
        yield 'simple time series' => [[
            '2024-01-01 12:00:00' => 123.45,
            '2024-01-01 12:01:00' => 123.46,
            '2024-01-01 12:02:00' => 123.47,
        ]];
    }

    #[DataProvider('corruptedTimeSeriesDataProvider')]
    public function testTryToCreateTimeSeriesWithCorruptedData(array $timeSeriesPayload): void
    {
        $this->expectException(CorruptedTimeSeriesException::class);

        TimeSeries::create($timeSeriesPayload);

    }

    public static function corruptedTimeSeriesDataProvider(): Generator
    {
        yield [
            ['non "Y-m-d H:i:s" key' => ['24-01-01 12:00:00' => 12.12]],
        ];
        yield [
            ['non numeric value' => ['2024-01-01 12:00:00' => 'essa']],
        ];
    }
}