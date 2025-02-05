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
#[CoversClass(LabeledDataset::class)]
#[CoversClass(InvalidDatasetTypeException::class)]
final class LabeledDatasetTest extends TestCase
{
    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testCreateLabeledDataset(array $samplesPayload, array $labelsPayload): void
    {
        $samples = Values::create($samplesPayload);
        $labels = Values::create($labelsPayload);

        $dataset = new LabeledDataset(samples: $samples, labels: $labels);

        $this->assertSame($samplesPayload, $dataset->samples()->data());
        $this->assertSame($labelsPayload, $dataset->labels()->data());
        $this->assertTrue($dataset->isLabeled());
        $this->assertFalse($dataset->isUnlabeled());
    }

    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testSplitLabeledDataset(array $samplesPayload, array $labelsPayload): void
    {
        $samples = Values::create($samplesPayload);
        $labels = Values::create($labelsPayload);

        $dataset = new LabeledDataset(samples: $samples, labels: $labels);

        $ratio = RealNumber::create(0.5);
        [$left, $right] = $dataset->split($ratio);

        $this->assertSame([$samplesPayload[0]], $left->samples()->data());
        $this->assertSame([$samplesPayload[1]], $right->samples()->data());
        $this->assertSame([3.0], $left->labels()->data());
        $this->assertSame([7.0], $right->labels()->data());
    }

    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testStackTwoDatasets(array $samplesPayload, array $labelsPayload): void
    {
        $samples = Values::create($samplesPayload);
        $labels = Values::create($labelsPayload);

        $datasets = new Datasets(
            new LabeledDataset(samples: $samples, labels: $labels),
        );

        $dataset = new LabeledDataset(samples: $samples, labels: $labels);
        $dataset = $dataset->stack($datasets);

        $this->assertSame(
            array_merge($samplesPayload, $samplesPayload),
            $dataset->samples()->data(),
        );
        $this->assertSame(
            array_merge($labelsPayload, $labelsPayload),
            $dataset->labels()->data(),
        );
    }

    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testTryToStackUnlabeledDatasetWithLabeledOne(array $samplesPayload, array $labelsPayload): void
    {
        $this->expectException(InvalidDatasetTypeException::class);
        $this->expectExceptionMessage('Expected labeled dataset');

        $samples = Values::create($samplesPayload);

        $datasets = new Datasets(
            new UnlabeledDataset(samples: $samples),
        );

        $dataset = new LabeledDataset(samples: $samples, labels: Values::create($labelsPayload));
        $dataset->stack($datasets);
    }

    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testBatchDataset(array $samplesPayload, array $labelsPayload): void
    {
        $samples = Values::create($samplesPayload);
        $labels = Values::create($labelsPayload);

        $dataset = new LabeledDataset(samples: $samples, labels: $labels);

        $quantity = RealNumber::create(2);
        [$left, $right] = $dataset->batch($quantity);

        $this->assertSame([[1.0, 2.0]], $left->samples()->data());
        $this->assertSame([3.0], $left->labels()->data());

        $this->assertSame([[3.0, 4.0]], $right->samples()->data());
        $this->assertSame([7.0], $right->labels()->data());
    }

    #[DataProvider('samplesAndLabelsDataProvider')]
    public function testRandomizeDataset(array $samplesPayload, array $labelsPayload): void
    {
        $samples = Values::create($samplesPayload);
        $labels = Values::create($labelsPayload);

        $dataset = new LabeledDataset($samples, $labels);
        $randomizedDataset = $dataset->randomize();

        $this->assertCount(2, $randomizedDataset->samples()->data(), 'The number of samples should remain the same.');
        $this->assertCount(2, $randomizedDataset->labels()->data(), 'The number of labels should remain the same.');

        $originalSamples = $samples->data();
        $originalLabels = $labels->data();
        $randomizedSamples = $randomizedDataset->samples()->data();
        $randomizedLabels = $randomizedDataset->labels()->data();

        // Ensure that the samples and labels are still associated correctly
        foreach ($randomizedSamples as $index => $sample) {
            $originalIndex = array_search($sample, $originalSamples);
            $this->assertSame($originalLabels[$originalIndex], $randomizedLabels[$index], 'The labels should remain associated with their samples.');
        }
    }

    public static function samplesAndLabelsDataProvider(): Generator
    {
        yield 'samples as values list and labels as its sum' => [
            [[1.0, 2.0], [3.0, 4.0]],
            [3.0, 7.0],
        ];
    }
}