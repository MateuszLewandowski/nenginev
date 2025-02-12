<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\MatrixInitializer\He;
use App\ComputationalIntelligence\Model\Network\Dense;
use App\ComputationalIntelligence\Model\Network\Gradient;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Model\Parameter;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Tensor;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dense::class)]
#[CoversClass(Adam::class)]
#[CoversClass(Gradient::class)]
final class DenseTest extends TestCase
{
    #[DataProvider('denseProvider')]
    public function testInitializeDenseUnit(Dense $dense): void
    {
        $inputNeurons = Neurons::create([1e-4, 1e-4]);
        $neurons = $dense->initialize($inputNeurons);

        $this->assertSame([1e-3, 1e-3], $neurons->data());

        foreach ($dense->weights->primitive() as $row) {
            foreach ($row as $value) {
                $this->assertGreaterThanOrEqual(1e-3, $value);
            }
        }
    }

    #[DataProvider('denseProvider')]
    public function testCalculateGradient(Dense $dense): void
    {
        $threshold = 10.0;
        $inputNeurons = Neurons::create([1e-4, 1e-4]);
        $dense->initialize($inputNeurons);

        $gradient = $dense->gradient($dense->weights, Matrix::create([
            [1.0, 2.0],
            [3.0, 4.0],
        ]));

        $this->assertTrue($this->verifyIfEveryValueInGradientBelongsToTheBounds($gradient, $threshold));
    }

    #[DataProvider('denseProvider')]
    public function testGenerateBackpropagation(Dense $dense): void
    {
        $examplePayload = Matrix::example();
        $inputNeurons = Neurons::create([1e-4, 1e-4]);
        $dense->initialize($inputNeurons);

        $adam = Adam::default();
        $adam->initialize($dense->weights);
        $adam->initialize($dense->bias);

        $dense->feedForward($examplePayload);
        $gradient = $dense->backPropagation($adam, $examplePayload, new RealNumber(1.0));

        $this->assertInstanceOf(Matrix::class, $gradient->value);
        $this->assertSame(2, $gradient->value->rows());
        $this->assertSame(2, $gradient->value->columns());
        $this->verifyIfEveryValueInGradientBelongsToTheBounds($gradient->value, 5.0);
    }

    #[DataProvider('denseProvider')]
    public function testTouchDenseForUsingTrainedAlreadyModel(Dense $dense): void
    {
        $examplePayload = Matrix::example();
        $inputNeurons = Neurons::create([1e-4, 1e-4]);
        $dense->initialize($inputNeurons);

        $result = $dense->touch($examplePayload);
        $this->assertSame(2, $result->rows());
        $this->assertSame(2, $result->columns());
        $this->verifyIfEveryValueInGradientBelongsToTheBounds($result, 5.0);
    }

    private function verifyIfEveryValueInGradientBelongsToTheBounds(Tensor $gradient, float $threshold): bool
    {
        foreach ($gradient->primitive() as $row) {
            foreach ($row as $value) {
                if (abs($value) >= $threshold) {
                    return false;
                }
            }
        }

        return true;
    }


    #[DataProvider('denseProvider')]
    public static function denseProvider(): Generator
    {
        yield [
            new Dense(
                Neurons::create([1e-3, 1e-3]),
                new Parameter(1e-4),
                new He(),
            ),
        ];
    }
}