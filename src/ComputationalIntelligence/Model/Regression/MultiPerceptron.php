<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;

use App\ComputationalIntelligence\Dataset\LabeledDataset;
use App\ComputationalIntelligence\Model\EvaluationFunction\CostFunction;
use App\ComputationalIntelligence\Network;
use App\Math\RealNumber;
use App\Math\Tensor\Matrix;
use App\Math\Values;

final class MultiPerceptron implements RegressionModel
{
    private RealNumber $epoch;
    private RealNumber $bestEpoch;
    private RealNumber $step;
    private RealNumber $cost;
    private RealNumber $bestCost;
    private LabeledDataset $trainingDataset;
    private LabeledDataset $testingDataset;

    public function __construct(
        private readonly Config $config,
        private readonly Network $network,
        private readonly LabeledDataset $dataset,
        private readonly CostFunction $costFunction,
    ) {
        $this->epoch = RealNumber::one();
        $this->step = RealNumber::one();
        $this->cost = RealNumber::infinity()->negate();
        $this->bestEpoch = RealNumber::zero();
        $this->bestCost = RealNumber::infinity();
    }

    public function initialize(): void
    {
        [$this->trainingDataset, $this->testingDataset] = $this->dataset->randomize()->split($this->config->holdOut);
        $this->network->initialize();
    }

    public function train(): RealNumber
    {
        $batches = $this->trainingDataset->batch($this->network->inputStreamLength());

        $previousLoss = RealNumber::infinity();
        $batchesCount = RealNumber::lengthOf($batches);

        do {
            $loss = RealNumber::zero();
            foreach ($batches as $batch) {
                $loss = $loss->add($this->network->cycle($batch, $this->epoch));
            }

            $loss = $loss->divide($batchesCount);
            $prediction = $this->predict($this->testingDataset);
            $cost = $this->evaluate($prediction, $this->testingDataset->labels());
            $this->cost = $cost;

            if ($cost->lessThanOrEquals(RealNumber::zero())) {
                break;
            }

            if ($cost->greaterThan($this->bestCost)) {
                $this->bestCost = $cost;
                $this->bestEpoch = $this->epoch;
                $this->step = RealNumber::zero();
            } else {
                $this->step = $this->step->increment();
            }

            if ($this->evaluateStopCondition($loss, $previousLoss)) {
                break;
            }

            $this->epoch = $this->epoch->increment();
            $previousLoss = $loss;
        } while ($this->epoch->lessThan($this->config->epochs));

        return $loss;
    }

    public function test(): RealNumber
    {
        return $this->evaluate($this->predict($this->testingDataset), $this->testingDataset->labels());
    }

    public function predict(LabeledDataset $features): Values
    {
        return $this->network->touch(
            Matrix::create($features->samples()->data())->transpose()
        )->values()->column(0);
    }

    public function evaluate(Values $features, Values $labels): RealNumber
    {
        return $this->costFunction->evaluate($features, $labels);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'config' => $this->config->jsonSerialize(),
                'network' => $this->network->jsonSerialize(),
                'dataset' => $this->dataset->jsonSerialize(),
                'costFunction' => $this->costFunction->jsonSerialize(),
            ],
            'meta' => [
                'final' => [
                    'epoch' => $this->epoch->jsonSerialize(),
                    'cost' => $this->cost->jsonSerialize(),
                    'step' => $this->step->jsonSerialize(),
                ],
                'best' => [
                    'epoch' => $this->bestEpoch->jsonSerialize(),
                    'cost' => $this->bestCost->jsonSerialize(),
                ],
            ],
        ];
    }

    private function evaluateStopCondition(RealNumber $loss, RealNumber $previousLoss): bool
    {
        return $this->step->greaterOrEquals($this->config->window)
            && $previousLoss->subtract($loss)->abs()->lessThan($this->config->minimumChange);
    }
}
