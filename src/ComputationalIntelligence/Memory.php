<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence;

use App\Tests\Unit\ComputationalIntelligence\Exception\MissingMemoryAddressException;
use Symfony\Component\Uid\Uuid;

final class Memory
{
    public function __construct(
        private array $memory = [],
    ) {
    }

    public function get(Uuid $id): mixed
    {
        $address = $id->toRfc4122();

        if (!isset($this->memory[$address])) {
            throw new MissingMemoryAddressException($id);
        }

        return $this->memory[$address];
    }

    public function set(Uuid $id, mixed $value): void
    {
        $this->memory[$id->toRfc4122()] = $value;
    }

    public function remove(Uuid $id): void
    {
        unset($this->memory[$id->toRfc4122()]);
    }

    public function push(Uuid $id, mixed $value): void
    {
        $this->memory[$id->toRfc4122()][] = $value;
    }

    public function first(Uuid $id): mixed
    {
        return reset($this->memory[$id->toRfc4122()]);
    }

    public function last(Uuid $id): mixed
    {
        return end($this->memory[$id->toRfc4122()]);
    }
}