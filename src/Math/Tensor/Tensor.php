<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use App\Math\Operation\Algebraic;
use App\Math\Operation\Arithmetical;
use App\Math\Operation\Clipable;
use App\Math\Operation\Comparable;
use App\Math\Operation\Compute;
use App\Math\Operation\Reducible;
use App\Math\Operation\Statistic;
use App\Math\Operation\Trigonometrical;
use App\Math\Tensor\Exception\IncompatibleTensorException;
use App\Math\Values;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

abstract readonly class Tensor implements
    JsonSerializable,
    Algebraic
//    Arithmetical,
//    Clipable,
//    Comparable,
//    Reducible,
//    Statistic,
//    Trigonometrical
{
    private Uuid $uid;

    protected function __construct(
        protected Values $values,
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

    private function fn(Tensor $first, Tensor $second, callable $fn): Tensor
    {
        if (!$first->isCompatible($second)) {
            throw new IncompatibleTensorException($first, $second);
        }

        return match (true) {
            $first->isMatrix() && $second->isMatrix() => /* @return Matrix */ Compute::matrices(/* @var Matrix $first */ $first, /* @var Matrix $second */ $second, $fn),

            $first->isMatrix() && $second->isVector() => /* @return Matrix */ Compute::matrixWithVector(/* @var Matrix $first */ $first, /* @var Vector $second */ $second, $fn),
            $first->isVector() && $second->isMatrix() => /* @return Matrix */ Compute::matrixWithVector(/* @var Matrix $second */ $second, /* @var Vector $first */ $first, $fn),

            $first->isMatrix() && $second->isScalar() => /* @return Matrix */ Compute::matrixWithScalar(/* @var Matrix $first */ $first, /* @var Scalar $second */ $second, $fn),
            $first->isScalar() && $second->isMatrix() => /* @return Matrix */ Compute::matrixWithScalar(/* @var Matrix $second */ $second, /* @var Scalar $first */ $first, $fn),

            $first->isVector() && $second->isVector() => /* @return Vector */ Compute::vectors(/* @var Vector $first */ $first, /* @var Vector $second */ $second, $fn),
            $first->isVector() && $second->isScalar() => /* @return Vector */ Compute::vectorWithScalar(/* @var Vector $first */ $first, /* @var Scalar $second */ $second, $fn),
            $first->isScalar() && $second->isVector() => /* @return Vector */ Compute::vectorWithScalar(/* @var Vector $second */ $second, /* @var Scalar $first */ $first, $fn),

            $first->isScalar() && $second->isScalar() => /* @return Scalar */ Compute::scalars(/* @var Scalar $second */ $second, /* @var Scalar $first */ $first, $fn),
        };
    }
}