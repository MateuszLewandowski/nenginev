<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Regression;


use App\ComputationalIntelligence\Model\Parameter;

final class Config
{
    public function __construct(
        public ?Parameter $batches,
        public ?Parameter $batchSize,
        public ?Parameter $alpha,
        public ?Parameter $epochs,
        public ?Parameter $minimumChange,
        public ?Parameter $window,
        public ?Parameter $holdOut,
    ) {
        $this->batches ??= $this->defaultBatches();
        $this->batchSize ??= $this->defaultBatchSize();
        $this->alpha ??= $this->defaultAlpha();
        $this->epochs ??= $this->defaultEpochs();
        $this->minimumChange ??= $this->defaultMinimumChange();
        $this->window ??= $this->defaultWindow();
        $this->holdOut ??= $this->defaultHoldOut();
    }

    public function jsonSerialize(): array
    {
        return [
            'batches' => $this->batches->jsonSerialize(),
            'batchSize' => $this->batchSize->jsonSerialize(),
            'alpha' => $this->alpha->jsonSerialize(),
            'epochs' => $this->epochs->jsonSerialize(),
            'minimumChange' => $this->minimumChange->jsonSerialize(),
            'window' => $this->window->jsonSerialize(),
            'holdOut' => $this->holdOut->jsonSerialize(),
        ];
    }

    private function defaultBatches(): Parameter
    {
        return new Parameter(64);
    }

    private function defaultBatchSize(): Parameter
    {
        return new Parameter(128);
    }

    private function defaultAlpha(): Parameter
    {
        return new Parameter(1e-4);
    }

    private function defaultEpochs(): Parameter
    {
        return new Parameter(1e3);
    }

    private function defaultMinimumChange(): Parameter
    {
        return new Parameter(1e-4);
    }

    private function defaultWindow(): Parameter
    {
        return new Parameter(5);
    }

    private function defaultHoldOut(): Parameter
    {
        return new Parameter(.2);
    }
}