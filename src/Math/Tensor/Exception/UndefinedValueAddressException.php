<?php

declare(strict_types=1);

namespace App\Math\Tensor\Exception;

use RuntimeException;

final class UndefinedValueAddressException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value address is not defined');
    }
}