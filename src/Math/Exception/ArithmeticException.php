<?php

declare(strict_types=1);

namespace App\Math\Exception;

use InvalidArgumentException;

final class ArithmeticException extends InvalidArgumentException
{
    public static function divideByZero(): self
    {
        return new self('Division by zero');
    }
}