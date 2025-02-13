<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset;

use App\ComputationalIntelligence\Dataset\Exception\InvalidDatasetTypeException;
use App\Math\RealNumber;
use App\Math\Values;

final readonly class LabeledDataset extends Dataset
{
    public function __construct(
        Values $samples,
        private Values $labels
    ) {
        parent::__construct($samples);
    }

    public static function create(array $samples, array $labels): self
    {
        return new self(Values::create($samples), Values::create($labels));
    }

    /** @return float[] */
    public function classes(): array
    {
        return array_values(array_unique($this->labels->data(), SORT_REGULAR));
    }

    /** @return LabeledDataset[] */
    public function split(RealNumber $ratio): array
    {
        $i = (int) floor($ratio->value * $this->samples->length());

        $left = new self(
            $this->samples->slice(0, $i),
            $this->labels->slice(0, $i),
        );

        $right = new self(
            $this->samples->slice($i),
            $this->labels->slice($i),
        );

        return [$left, $right];
    }

    public function stack(Datasets $datasets): LabeledDataset
    {
        $labels = $this->labels;
        $samples = $this->samples;

        foreach ($datasets->values() as /** @var LabeledDataset $dataset */ $dataset) {
            if (!$dataset->isLabeled()) {
                throw InvalidDatasetTypeException::expectedLabeled();
            }
            if ($dataset->samples->empty()) {
                continue;
            }
            $labels = $labels->merge($dataset->labels());
            $samples = $samples->merge($dataset->samples());
        }

        return new self($samples, $labels);
    }

    /** @return LabeledDataset[] */
    public function batch(RealNumber $quantity): array
    {
        $batchSize = (int) ceil($this->samples->length() / $quantity->asInteger());

        return array_map(
            [$this, 'create'],
            array_chunk($this->samples->data(), $batchSize),
            array_chunk($this->labels->data(), $batchSize),
        );
    }

    public function randomize(): LabeledDataset
    {
        $samples = $this->samples->data();
        $labels = $this->labels->data();
        $order = range(0, count($samples) - 1);
        shuffle($order);

        $shuffledSamples = [];
        $shuffledLabels = [];
        foreach ($order as $index) {
            $shuffledSamples[] = $samples[$index];
            $shuffledLabels[] = $labels[$index];
        }

        return new self(Values::create($shuffledSamples), Values::create($shuffledLabels));
    }

    public function labels(): Values
    {
        return $this->labels;
    }

    public function isLabeled(): bool
    {
        return true;
    }

    public function isUnlabeled(): bool
    {
        return false;
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'samples' => $this->samples()->length(),
                'labels' => $this->labels()->length(),
            ],
        ];
    }
}