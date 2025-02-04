<?php

declare(strict_types=1);

namespace App\Tests\Unit\ComputationalIntelligence\Exception;

use RuntimeException;
use Symfony\Component\Uid\Uuid;

final class MissingMemoryAddressException extends RuntimeException
{
    public function __construct(Uuid $id)
    {
        parent::__construct(sprintf('Memory address %s not found.', $id->toRfc4122()));
    }
}