<?php

declare(strict_types=1);

namespace App\Math\Tensor;

use JsonSerializable;
use Symfony\Component\Uid\Uuid;

abstract readonly class Tensor implements JsonSerializable
{
    private Uuid $uid;

    protected function __construct(
        protected TensorType $type,
    ) {
        $this->uid = Uuid::v7();
    }

    abstract public static function create(float|array $input): self;
    abstract public function isCompatible(self $tensor): bool;
    abstract public function primitive(): mixed;
    abstract public function size(): int;

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
}