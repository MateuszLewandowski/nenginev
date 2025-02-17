<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\Converter\Normalizer;
use App\ComputationalIntelligence\Dataset\Converter\Splitter;
use App\ComputationalIntelligence\Dataset\File\Decoder\FileContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoderArguments;
use App\ComputationalIntelligence\Dataset\Generator\RandomTimeSeriesGenerator;
use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Model\Application\TrainModelRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final readonly class TrainingProcessor
{
    private FileContentDecoder $fileContentDecoder;
    private Normalizer $normalizer;
    private Splitter $splitter;
    private RandomTimeSeriesGenerator $randomTimeSeriesGenerator;

    public function __construct(
    ) {
        $this->fileContentDecoder = new FileContentDecoder(
            new JsonContentDecoder(
                (new JsonContentDecoderArguments())->jsonSerialize()
            )
        );
        $this->splitter = new Splitter();
        $this->normalizer = new Normalizer();
        $this->randomTimeSeriesGenerator = new RandomTimeSeriesGenerator();
    }

    public function __invoke(Request $request): void
    {
        $trainModelRequest = TrainModelRequest::fromHttpRequest($request);

        $fileName = $this->randomTimeSeriesGenerator->generate(200);
        $uploadedFile = new UploadedFile($fileName, 'test.json', 'application/csv', null, true);

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

        dd($score, $test, $multiPerceptron->report());
    }
}