<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Operation\Algebraic;
use App\Math\Operation\Arithmetical;
use App\Math\Operation\Clipable;
use App\Math\Operation\Comparable;
use App\Math\Operation\Reducible;
use App\Math\Operation\Statistic;
use App\Math\Operation\Trigonometrical;
use App\Math\Values;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

abstract readonly class Tensor implements
    JsonSerializable
//    Algebraic,
//    Arithmetical,
//    Clipable,
//    Comparable,
//    Reducible,
//    Statistic,
//    Trigonometrical
{
    private Uuid $uid;

    protected function __construct(
        protected TensorType $type,
    ) {
        $this->uid = Uuid::v7();
    }

    abstract public static function create(float|array $input): self;
    abstract public function isCompatible(self $tensor): bool;
    abstract public function size(): int;
    abstract public function values(): Values;
    abstract public function primitive(): mixed;
    abstract public function dimension(): int;

    public function id(): Uuid
    {
        return $this->uid;
    }

    public function type(): TensorType
    {
        return $this->type;
    }

    public function isScalar(): bool
    {
        return $this->type->isScalar();
    }

    public function isVector(): bool
    {
        return $this->type->isVector();
    }

    public function isMatrix(): bool
    {
        return $this->type->isMatrix();
    }
}