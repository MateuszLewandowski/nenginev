<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder\Exception;

use RuntimeException;
use Throwable;

final class ContentDecodingException extends RuntimeException
{
    public function __construct(Throwable $previous)
    {
        parent::__construct(message: 'The content could not be decoded.', previous: $previous);
    }
}