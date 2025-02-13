<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Network;

use App\Math\Tensor\Matrix;

final readonly class Dropout implements FeedForwarding, Layer
{
    private Matrix $mask;

    public function __construct(
        private Coefficient $coefficient,
    ) {
    }

    public function feedForward(Matrix $input): Matrix
    {
        $this->mask = Matrix::random(...$input->shape())
            ->greater($this->coefficient->ratio())
            ->multiply($this->coefficient->scale());

        return $input->multiply($this->mask);
    }


    public function backPropagation(Matrix $gradient): Matrix
    {
        return $gradient->multiply($this->mask);
    }

    public function jsonSerialize(): array
    {
        return [
            'type' => self::class,
            'args' => [
                'coefficient' => $this->coefficient->jsonSerialize(),
            ],
        ];
    }
}