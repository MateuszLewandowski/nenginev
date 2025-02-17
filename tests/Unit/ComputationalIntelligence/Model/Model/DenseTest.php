<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Model\Model;

use App\ComputationalIntelligence\Model\Initializer\He;
use App\ComputationalIntelligence\Model\Network\Dense;
use App\ComputationalIntelligence\Model\Network\Neurons;
use App\ComputationalIntelligence\Model\Optimizer\Adam;
use App\ComputationalIntelligence\Parameter;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Tensor;
use Generator;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(Dense::class)]
#[CoversClass(Adam::class)]
final class DenseTest extends TestCase
{
    #[DataProvider('denseProvider')]
    public function testInitializeDenseUnit(Dense $dense): void
    {
        $inputNeurons = Neurons::create(2);
        $neurons = $dense->initialize($inputNeurons);

        $this->assertSame(2, $neurons->quantity());

        foreach ($dense->weights->primitive() as $row) {
            foreach ($row as $value) {
                $this->assertGreaterThanOrEqual(1e-3, $value);
            }
        }
    }

    #[DataProvider('denseProvider')]
    public function testGenerateBackpropagation(Dense $dense): void
    {
        $examplePayload = Matrix::example();
        $inputNeurons = Neurons::create(2);
        $dense->initialize($inputNeurons);

        $adam = Adam::default();
        $adam->initialize($dense->weights);
        $adam->initialize($dense->bias);

        $dense->feedForward($examplePayload);
        $gradient = $dense->backPropagation($adam, $examplePayload, new RealNumber(1.0));

        $this->assertSame(2, $gradient->rows());
        $this->assertSame(2, $gradient->columns());
        $this->assertTrue($this->verifyIfEveryValueInGradientIsPossitiveNumber($gradient));
    }

    #[DataProvider('denseProvider')]
    public function testTouchDenseForUsingTrainedAlreadyModel(Dense $dense): void
    {
        $examplePayload = Matrix::example();
        $inputNeurons = Neurons::create(2);
        $dense->initialize($inputNeurons);

        $result = $dense->touch($examplePayload);
        $this->assertSame(2, $result->rows());
        $this->assertSame(2, $result->columns());
        $this->assertTrue($this->verifyIfEveryValueInGradientIsPossitiveNumber($result));
    }

    private function verifyIfEveryValueInGradientIsPossitiveNumber(Tensor $gradient): bool
    {
        foreach ($gradient->primitive() as $row) {
            foreach ($row as $value) {
                if ($value <= .0) {
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
                Neurons::create(2),
                new Parameter(1e-4),
                new He(),
            ),
        ];
    }
}