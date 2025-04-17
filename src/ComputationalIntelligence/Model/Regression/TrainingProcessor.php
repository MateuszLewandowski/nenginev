<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\Converter\Normalizer;
use App\ComputationalIntelligence\Dataset\Converter\Splitter;
use App\ComputationalIntelligence\Dataset\File\Decoder\Csv\CsvContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Csv\CsvFileContentDecoderArguments;
use App\ComputationalIntelligence\Dataset\File\Decoder\FileContentDecoder;
use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Model\Application\TrainModelRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final readonly class TrainingProcessor
{
    private FileContentDecoder $fileContentDecoder;
    private Normalizer $normalizer;
    private Splitter $splitter;

    public function __construct(
    ) {
        $this->fileContentDecoder = new FileContentDecoder(
            new CsvContentDecoder(
                (new CsvFileContentDecoderArguments())->jsonSerialize()
            )
        );
        $this->splitter = new Splitter();
        $this->normalizer = new Normalizer();
    }

    public function __invoke(Request $request): void
    {
        $trainModelRequest = TrainModelRequest::fromHttpRequest($request);

        $uploadedFile = new UploadedFile('super_shop_dataset.csv', 'test.csv', 'application/csv', null, true);

        $timeSeries = $this->fileContentDecoder->decode($uploadedFile);

        $normalizedTimeSeries = $this->normalizer->minMaxFeatureScaling($timeSeries);

        [$samples, $labels] = $this->splitter->split(
            timeSeries: $normalizedTimeSeries,
            batches: $trainModelRequest->config->batches,
            batchSize: $trainModelRequest->config->batchSize,
        );

        $labeledDataSet = new LabeledDataSet($samples, $labels);

        $multiPerceptron = new MultiPerceptron(
            $trainModelRequest->config,
            $trainModelRequest->network,
            $labeledDataSet,
            $trainModelRequest->costFunction
        );

        $multiPerceptron->initialize();
        $score = $multiPerceptron->train();
        $test = $multiPerceptron->test();

        # dd($score, $test, $multiPerceptron->jsonSerialize());
    }
}