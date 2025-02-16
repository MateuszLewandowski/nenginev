<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Model\Exception;

use InvalidArgumentException;

final class MissingHiddenLayerException extends InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct('No hidden layers has been provided.');
    }
}