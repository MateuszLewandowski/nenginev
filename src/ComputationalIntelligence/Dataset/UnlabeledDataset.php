<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\Exception\InvalidDatasetTypeException;
use App\Math\RealNumber;
use App\Math\Values;

final readonly class UnlabeledDataset extends Dataset
{
    /** @return UnlabeledDataset[] */
    public function split(RealNumber $ratio): array
    {
        $i = (int) floor($ratio->value * $this->samples->length());

        return [
            new self($this->samples->slice(0, $i)),
            new self($this->samples->slice($i)),
        ];
    }

    public function stack(Datasets $datasets): UnlabeledDataset
    {
        $samples = $this->samples;
        foreach ($datasets->values() as /** @var UnlabeledDataset $dataSet */ $dataSet) {
            if (!$dataSet->isUnlabeled()) {
                throw InvalidDatasetTypeException::expectedUnlabeled();
            }

            if ($dataSet->samples->empty()) {
                continue;
            }

            $samples = $samples->merge($dataSet->samples);
        }

        return new self($samples);
    }

    /** @return UnlabeledDataset[] */
    public function batch(RealNumber $quantity): array
    {
        $batchSize = (int) ceil($this->samples->length() / $quantity->asInteger());

        return array_map(
            static fn(Values $chunk): UnlabeledDataset => new self($chunk),
            $this->samples()->chunk($batchSize)
        );
    }

    public function randomize(): UnlabeledDataset
    {
        $samples = $this->samples->data();
        $order = range(0, count($samples) - 1);
        shuffle($order);

        $shuffledSamples = [];
        foreach ($order as $index) {
            $shuffledSamples[] = $samples[$index];
        }

        return new self(Values::create($shuffledSamples));
    }

    public function isLabeled(): bool
    {
        return false;
    }

    public function isUnlabeled(): bool
    {
        return true;
    }

    public function jsonSerialize(): array
    {
        return [];
    }
}