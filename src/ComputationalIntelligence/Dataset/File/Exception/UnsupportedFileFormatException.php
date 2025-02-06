<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Exception;

use RuntimeException;

final class UnsupportedFileFormatException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Unsupported file format');
    }
}
