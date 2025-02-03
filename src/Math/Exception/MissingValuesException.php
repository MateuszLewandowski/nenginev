<?php

declare(strict_types=1);

namespace App\Math\Exception;

use InvalidArgumentException;

final class MissingValuesException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('Values must not be empty');
    }
}