<?php

declare(strict_types=1);

namespace App\ComputationalIntelligence\Dataset\File\Decoder;

interface ContentDecoderArguments extends \JsonSerializable
{
    public function intendedFor(): string;
}