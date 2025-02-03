<?php

declare(strict_types=1);

namespace App\Math\Tensor\Exception;

use App\Math\Tensor\Tensor;
use InvalidArgumentException;

final class IncompatibleTensorException extends InvalidArgumentException
{
    public function __construct(Tensor $first, Tensor $second)
    {
        parent::__construct(
            sprintf(
                'Given tensor %s is not compatible with %s tensor',
                $first->type()->value,
                $second->type()->value,
            )
        );
    }
}
