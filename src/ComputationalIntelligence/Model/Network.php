<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model;

use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Model\Network\Continuous;
use App\ComputationalIntelligence\Model\Network\Hidden;
use App\ComputationalIntelligence\Model\Network\Stream;
use App\ComputationalIntelligence\Model\Optimizer\Optimizer;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Tensor\Scalar;

final readonly class Network
{
    /** @var Hidden[] $hiddens */
    private iterable $hiddens;

    public function __construct(
        private Stream $stream,
        private Continuous $continuous,
        private Optimizer $optimizer,
        Hidden ...$hidden,
    ) {
        $this->hiddens = $hidden;
    }

    public function initialize(): void
    {
        $input = $this->stream->neurons;
        foreach ($this->hiddens as $hidden) {
            $hidden->initialize($input, $this->optimizer);
        }
    }

    public function cycle(LabeledDataset $dataset, RealNumber $epoch): RealNumber
    {
        $samples = $dataset->samples()->data();
        $label = $dataset->labels()->column(0)->data();

        $this->feedForward(Matrix::create($samples));

        return RealNumber::create($this->backPropagation(Scalar::create($label), $epoch)->primitive());
    }

    public function feedForward(Matrix $input): Matrix
    {
        $stream = $this->stream->feedForward($input);
        foreach ($this->hiddens as $hidden) {
            $stream = $hidden->feedForward($stream);
        }

        return $stream;
    }

    public function backPropagation(Scalar $label, RealNumber $epoch): Scalar
    {
        $output = $this->continuous->backPropagation($label);
        $gradient = $output->gradient;
        foreach ($this->hiddens as $hidden) {
            $gradient = $hidden->backPropagation($this->optimizer, $gradient, $epoch);
        }

        return $output->loss;
    }

    public function touch(Matrix $input): Matrix
    {
        foreach ($this->hiddens as $hidden) {
            $input = $hidden->touch($input);
        }

        return $input;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'stream' => $this->stream->jsonSerialize(),
                'hiddens' => array_map(static fn(Hidden $hidden): array => $hidden->jsonSerialize(), $this->hiddens),
                'continuous' => $this->continuous->jsonSerialize(),
                'optimizer' => $this->optimizer->jsonSerialize(),
            ],
        ];
    }

    public function inputStreamLength(): RealNumber
    {
        return $this->stream->neuronsQuantity();
    }
}