<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset\Converter;

use App\ComputationalIntelligence\Dataset\Converter\Exception\TimeSeriesLengthException;
use App\ComputationalIntelligence\Dataset\Converter\Splitter;
use App\ComputationalIntelligence\Dataset\TimeSeries;
use App\Math\RealNumber;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class SplitterTest extends TestCase
{
    private Splitter $splitter;

    public function setUp(): void
    {
        $this->splitter = new Splitter();
    }

    #[DataProvider('dataSeriesProvider')]
    public function testSplitTimeSeriesValues(
        TimeSeries $timeSeries,
        RealNumber $batches,
        RealNumber $batchSize,
    ): void {
        $values = $timeSeries->values();
        [$samples, $labels] = $this->splitter->split($timeSeries, $batches, $batchSize);
        $samplesData = $samples->data();
        $labelsData = $labels->data();

        // first segment
        $this->assertSame([$values[0], $values[1]], $samplesData[0]);
        $this->assertSame($values[2], $labelsData[0]);

        // second segment
        $this->assertSame([$values[2], $values[3]], $samplesData[1]);
        $this->assertSame($values[4], $labelsData[1]);
    }

    public static function dataSeriesProvider(): Generator
    {
        yield 'to split we need (batches * batch size + batches - 1) time series length' => [
            new TimeSeries([
                '2024-01-01 12:00' => 100.0,
                '2024-01-01 12:01' => 200.0,
                '2024-01-01 12:31' => 500.0,
                '2024-01-01 12:34' => 2000.0,
                '2024-01-01 12:36' => 6000.0,
            ]),
            new RealNumber(2),
            new RealNumber(2),
        ];
    }

    #[DataProvider('tooShortDataSeriesProvider')]
    public function testTryToSplitTooShortTimeSeries(
        TimeSeries $timeSeries,
        RealNumber $batches,
        RealNumber $batchSize,
    ): void {
        $this->expectException(TimeSeriesLengthException::class);

        $this->splitter->split($timeSeries, $batches, $batchSize);
    }

    public static function tooShortDataSeriesProvider(): Generator
    {
        yield 'time series length does not meet the requirements' => [
            new TimeSeries([
                '2024-01-01 12:00' => 100.0,
                '2024-01-01 12:01' => 200.0,
                '2024-01-01 12:31' => 500.0,
            ]),
            new RealNumber(2),
            new RealNumber(4),
        ];
    }
}