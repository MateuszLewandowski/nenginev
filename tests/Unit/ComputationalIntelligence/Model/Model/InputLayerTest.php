<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Exception\DifferentFeaturesAndNeuronsQuantityException;
use App\ComputationalIntelligence\Model\Network\InputLayer;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\Math\Tensor\Matrix;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(InputLayer::class)]
final class InputLayerTest extends TestCase
{
    #[DataProvider('neuronsProvider')]
    public function testCreateInputLayerWithSomeNeurons(Neurons $neurons): void
    {
        $inputLayer = new InputLayer($neurons);

        $this->assertSame($neurons->data(), $inputLayer->neurons->data());
        $this->assertSame([
            'type' => InputLayer::class,
            'args' => [
                'neurons' => $neurons->data(),
            ],
        ], $inputLayer->jsonSerialize());
    }

    #[DataProvider('neuronsProvider')]
    public function testTouchLayerWhenUsingLearnedModel(Neurons $neurons): void
    {
        $inputLayer = new InputLayer($neurons);
        $matrix = Matrix::random();
        $output = $inputLayer->touch($matrix);

        $this->assertSame($matrix, $output);
    }

    #[DataProvider('neuronsProvider')]
    public function testTryToFeedForwardWithDifferentInputVectorLengthAndMatrixRows(Neurons $neurons): void
    {
        $this->expectException(DifferentFeaturesAndNeuronsQuantityException::class);

        $inputLayer = new InputLayer($neurons);
        $matrix = Matrix::create([
            [1.0, 2.0],
            [1.0, 2.0],
            [1.0, 2.0],
        ]);

        $inputLayer->feedForward($matrix);
    }

    #[DataProvider('neuronsProvider')]
    public function testFeedForwardInputDuringNetworkLearning(Neurons $neurons): void
    {
        $inputLayer = new InputLayer($neurons);
        $matrix = Matrix::create([
            [1.0, 2.0],
            [1.0, 2.0],
        ]);

        $output = $inputLayer->feedForward($matrix);

        $this->assertSame($matrix, $output);
    }

    public static function neuronsProvider(): \Generator
    {
        yield 'two neurons as input vector' => [Neurons::create([1.0, 2.0])];
    }
}