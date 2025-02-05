<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\Exception;

use RuntimeException;

final class InvalidDatasetTypeException extends RuntimeException
{
    public static function expectedLabeled(): self
    {
        return new self('Expected labeled dataset');
    }

    public static function expectedUnlabeled(): self
    {
        return new self('Expected unlabeled dataset');
    }
}