<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Optimizer;

use App\ComputationalIntelligence\Memory;
use App\ComputationalIntelligence\Model\Parameter;
use App\Math\RealNumber;
use App\Math\Tensor\Scalar;
use App\Math\Tensor\Tensor;
use Symfony\Component\DependencyInjection\Attribute\WhenNot;
use Symfony\Component\Uid\Uuid;

final class Adam implements Optimizer
{
    private readonly Memory $memory;

    public function __construct(
        private ?Scalar $learningRate,
        private ?Scalar $momentum,
        private ?Scalar $decay,
    ) {
        $this->learningRate ??= Scalar::create($this->defaultLearningRate()->value);
        $this->momentum ??= Scalar::create($this->defaultMomentum()->value);
        $this->decay ??= Scalar::create($this->defaultDecay()->value);

        $this->memory = new Memory();
    }

    public function initialize(Tensor $tensor): void
    {
        $base = $tensor::zeros(...$tensor->shape());
        $this->memory->set($tensor->id(), [$base, clone $base]);
    }

    public function optimize(Uuid $id, Tensor $gradient, RealNumber $epoch): Tensor
    {
        /**
         * @var Tensor $velocity
         * @var Tensor $norm,
         */
        [$velocity, $norm] = $this->memory->get($id);

        # first moment (moving average of gradients)
        $velocity = $velocity->multiply($this->momentum)->add(
            $gradient->multiply(Scalar::create(1.0)->subtract($this->momentum))
        );

        # second moment (average of the squares of the gradients)
        $norm = $norm->multiply($this->decay)->add(
            $gradient->square()->multiply(Scalar::create(1.0)->subtract($this->decay))
        );

        # bias correction
        $velocityHat = $velocity->divide(Scalar::create(1.0)->subtract($this->momentum->pow($epoch)));
        $normHat = $norm->divide(Scalar::create(1.0)->subtract($this->decay->pow($epoch)));

        $this->memory->set($id, [$velocity, $norm]);

        # weights update
        return $velocityHat->multiply($this->learningRate)->divide(
            $normHat->sqrt()->clipToMin(RealNumber::epsilon())
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => get_class($this),
            'args' => [
                'learningRate' => $this->learningRate->primitive(),
                'momentum' => $this->momentum->primitive(),
                'decay' => $this->decay->primitive(),
            ],
        ];
    }

    private function defaultLearningRate(): Parameter
    {
        return new Parameter(1e-4);
    }

    private function defaultMomentum(): Parameter
    {
        return new Parameter(1e-1);
    }

    private function defaultDecay(): Parameter
    {
        return new Parameter(1e-3);
    }

    #[WhenNot('production')]
    public function memory(): Memory
    {
        return $this->memory;
    }

    public static function default(): self
    {
        return new self(
            learningRate: Scalar::create(1e-4),
            momentum: Scalar::create(1e-1),
            decay: Scalar::create(1e-3),
        );
    }
}