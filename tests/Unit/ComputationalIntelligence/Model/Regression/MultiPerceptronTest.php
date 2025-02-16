<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoder;
use App\ComputationalIntelligence\Dataset\File\Decoder\Json\JsonContentDecoderArguments;
use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Model\ActivationFunction\ReLU;
use App\ComputationalIntelligence\Model\EvaluationFunction\MeanSquaredError;
use App\ComputationalIntelligence\Model\Initializer\He;
use App\ComputationalIntelligence\Model\Network\Coefficient;
use App\ComputationalIntelligence\Model\Network\Continuous;
use App\ComputationalIntelligence\Model\Network\Dense;
use App\ComputationalIntelligence\Model\Network\Dropout;
use App\ComputationalIntelligence\Model\Network\Hidden;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\ComputationalIntelligence\Model\Network\Sense;
use App\ComputationalIntelligence\Model\Network\Stream;
use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Model\Regression\Config;
use App\ComputationalIntelligence\Model\Regression\MultiPerceptron;
use App\ComputationalIntelligence\Network;
use App\ComputationalIntelligence\Parameter;
use App\Math\Values;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class MultiPerceptronTest extends TestCase
{
    #[DataProvider('defaultMultiPerceptronTest')]
    public function testTrain(MultiPerceptron $multiPerceptron): void
    {
        $multiPerceptron->initialize();
        $multiPerceptron->train();
    }

    public static function defaultMultiPerceptronTest(): Generator
    {
        $decoder = new JsonContentDecoder((new JsonContentDecoderArguments())->jsonSerialize());
        $filename = 'TinyTimeSeries.json';

        $tmp = tempnam(sys_get_temp_dir(), $filename);
        file_put_contents($tmp, $content, JSON_PRETTY_PRINT);

        yield [
            new MultiPerceptron(
                new Config(
                    batches: new Parameter(2),
                    batchSize: new Parameter(2),
                    holdOut: new Parameter(.2)
                ),
                new Network(
                    new Stream(Neurons::create([1e-4, 1e-4])),
                    new Continuous(new MeanSquaredError()),
                    new Adam(),
                    new Hidden(
                        new Dense(Neurons::create([1e-4, 1e-4, 1e-4, 1e-4]), new Parameter(1e-4), new He()),
                        new Sense(new ReLU()),
                        new Dropout(new Coefficient(.2)),
                    ),
                ),
                new LabeledDataset(
                    Values::create([1.0, 2.0, 3.0, 4.0, 1.0, 2.0, 3.0, 4.0]),
                    Values::create([1.0, 2.0, 1.0, 2.0]),
                ),
                new MeanSquaredError()
            ),
        ];
    }
}