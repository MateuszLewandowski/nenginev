<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\Dataset;
use App\ComputationalIntelligence\Dataset\Datasets;
use App\ComputationalIntelligence\Dataset\Exception\InvalidDatasetTypeException;
use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Dataset\UnlabeledDataset;
use App\Math\RealNumber;
use App\Math\Values;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dataset::class)]
#[CoversClass(UnlabeledDataset::class)]
#[CoversClass(InvalidDatasetTypeException::class)]
final class UnlabeledDatasetTest extends TestCase
{
    #[DataProvider('samplesDataProvider')]
    public function testCreateUnlabeledDataset(array $samplesPayload): void
    {
        $samples = Values::create($samplesPayload);

        $dataset = new UnlabeledDataset(samples: $samples);

        $this->assertSame($samplesPayload, $dataset->samples()->data());
        $this->assertTrue($dataset->isUnlabeled());
        $this->assertFalse($dataset->isLabeled());
    }

    #[DataProvider('samplesDataProvider')]
    public function testSplitUnlabeledDataset(array $samplesPayload): void
    {
        $samples = Values::create($samplesPayload);
        $dataset = new UnlabeledDataset(samples: $samples);

        $ratio = RealNumber::create(0.5);
        [$left, $right] = $dataset->split($ratio);

        $this->assertSame([$samplesPayload[0]], $left->samples()->data());
        $this->assertSame([$samplesPayload[1]], $right->samples()->data());
    }

    #[DataProvider('samplesDataProvider')]
    public function testStackTwoDatasets(array $samplesPayload): void
    {
        $samples = Values::create($samplesPayload);
        $datasets = new Datasets(
            new UnlabeledDataset(samples: $samples),
        );

        $dataset = new UnlabeledDataset(samples: $samples);
        $dataset = $dataset->stack($datasets);

        $this->assertSame(array_merge($samplesPayload, $samplesPayload), $dataset->samples()->data());
        $this->assertSame(count($samplesPayload) * 2, $dataset->samples()->length());
    }

    #[DataProvider('samplesDataProvider')]
    public function testTryToStackLabeledDatasetWithUnlabeledOne(array $samplesPayload): void
    {
        $this->expectException(InvalidDatasetTypeException::class);
        $this->expectExceptionMessage('Expected unlabeled dataset');

        $samples = Values::create($samplesPayload);
        $datasets = new Datasets(
            new LabeledDataset(samples: $samples, labels: Values::create([1.0, 2.0])),
        );

        $dataset = new UnlabeledDataset(samples: $samples);

        $dataset->stack($datasets);
    }

    #[DataProvider('samplesDataProvider')]
    public function testBatchDataset(array $samplesPayload): void
    {
        $samples = Values::create($samplesPayload);
        $dataset = new UnlabeledDataset(samples: $samples);

        $quantity = RealNumber::create(2);
        $batches = $dataset->batch($quantity);

        $this->assertCount(2, $batches);
        $this->assertSame([$samplesPayload[0]], $batches[0]->samples()->data());
        $this->assertSame([$samplesPayload[1]], $batches[1]->samples()->data());
    }

    #[DataProvider('samplesDataProvider')]
    public function testRandomizeDataset(array $samplesPayload): void
    {
        $samples = Values::create($samplesPayload);

        $dataset = new UnlabeledDataset($samples);
        $randomizedDataset = $dataset->randomize();

        $this->assertCount(2, $randomizedDataset->samples()->data(), 'The number of samples should remain the same.');
    }

    public static function samplesDataProvider(): Generator
    {
        yield 'samples as values list' => [
            [[1.0, 2.0, 3.0], [4.0, 5.0, 6.0]],
        ];
    }
}